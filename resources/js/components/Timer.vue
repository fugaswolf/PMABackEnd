<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-5">


                <div class="card">
                    <div class="card-body">

                        <form>
                            <div class="form-row align-content-center">
                                <div class="col-sm-3">
                                    <label class="form-inline" style="opacity: 0.0">Sumbit</label>
                                    <input v-model="form.description" type="text" placeholder="What are you doing?"
                                        name="description" class="form-control mr-1"
                                        :class="{ 'is-invalid': form.errors.has('description') }">
                                </div>
                                <div class="col-xs-1">
                                    <label class="form-inline" style="opacity: 0.0">Sumbit</label>
                                    <select type="text" v-model="form.project_id" name="project_id" id="project_id" v-on:change="projectSelected($event)"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('project_id') }">
                                        <option value="" selected>Choose project</option>
                                        <option v-for="project in projects.data" v-bind:value="project.id" :key="project.id">{{project.name}}</option>
                                    </select>
                                    <has-error :form="form" field="project_id"></has-error>
                                </div>
                                
                                <div class="col-xs-1" v-if="shownActivities" v-on:change="activitySelected($event)">
                                    <label class="form-inline" style="opacity: 0.0">Sumbit</label>
                                    <select v-model="form.activity_id" type="text" name="activity_id" id="activity_id"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('activity_id') }"
                                        place>
                                        <option value="" selected>Choose an activity</option>
                                        <!--<div v-if="shownActivities" >-->
                                            <option v-for="activity in shownActivities" :key="activity.id" v-bind:value="activity.id">{{activity.name}}</option>
                                        <!--</div>-->
                                    </select>
                                    <has-error :form="form" field="activity_id"></has-error>
                                </div>

                                <div class="col-xs-0">
                                    <label>Start</label>
                                    <input type="datetime-local" v-model="form.start_date" class="form-control"
                                        name="start_date" id="meeting-time"
                                        :class="{ 'is-invalid': form.errors.has('start_date') }">
                                </div>
                                <div class="col-xs-0">
                                    <label class="form-inline">End</label>
                                    <input type="datetime-local" v-model="form.end_date" class="form-control"
                                        name="end_date" id="meeting-time"
                                        :class="{ 'is-invalid': form.errors.has('end_date') }">
                                </div>
                                <div class="col-xs-1">
                                     <label class="form-inline" style="opacity: 0.0">Sumbit</label>
                                    <button type="button" @click.prevent="createEntry" class="btn btn-primary">Add entry</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>





                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Time entries</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Description</th>
                                    <th>Date Start</th>
                                    <th>Date End</th>
                                    <th>Duration</th>
                                    <th>Modify</th>
                                </tr>
                                <tr v-for="entry in entries.data" :key="entry.id">
                                    <td>{{entry.id}}</td>
                                    <td>{{entry.description}}</td>
                                    <td>{{entry.start_date}}</td>
                                    <td>{{entry.end_date}}</td>
                                    <td>{{entry.duration}}</td>
                                    <td>
                                        <a href="" @click="editModal(entry)">
                                            <i class="fa fa-pencil-alt blue"></i>
                                        </a>
                                        /
                                        <a href="" @click="deleteEntry(entry.id)">
                                            <i class="fa fa-trash-alt red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="entries" @pagination-change-page="getResults"></pagination>
                    </div>

                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="editEntry" tabindex="-1" role="dialog" aria-labelledby="editEntry"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEntry">Edit entry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="updateEntry">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Projects</label>
                                <select type="text" v-model.lazy="form.project_id" name="project_id" id="project_id" v-on:change="projectSelected($event)"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('project_id') }">
                                        <option value="" selected>Choose project</option>
                                        <option v-for="project in projects.data" v-bind:value="project.id" :key="project.id">{{project.name}}</option>
                                    </select>
                                    <has-error :form="form" field="project_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Activities</label>
                                 <select v-model.lazy="form.activity_id" type="text" name="activity_id" id="activity_id"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('activity_id') }"
                                        place>
                                        <option value="" selected>Choose an activity</option>
                                        <!--<div v-if="shownActivities" >-->
                                            <option v-for="activity in shownActivities" :key="activity.id" v-bind:value="activity.id">{{activity.name}}</option>
                                        <!--</div>-->
                                    </select>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea v-model="form.comment" type="text" name="comment" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('comment') }"></textarea>

                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Create project</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                shownActivities: [],
                editmode: false,
                entries: {},
                projects: {},
                // projectsWithActivities: {},
                // activities: {},
                form: new Form({
                    id: '',
                    user_id: '',
                    project_id: '',
                    activity_id: '',
                    start_date: '',
                    end_date: '',
                    duration: '',
                    description: '',
                    remember: false
                })
            }
        },
        methods: {
            activitySelected(event) {
                console.log(event.target.value);
                this.form.activity_id = event.target.value;
            },
            projectSelected (event) {
                const projectId = event.target.value;
                axios
                    .get('api/activitiesByProject/'+projectId)
                    .then(({
                        data
                    }) => (this.shownActivities = data));
            },
            getResults(page = 1) {
                axios.get('api/entries?page=' + page)
                    .then(response => {
                        this.entries = response.data;
                    });
            },
            updateEntry(id) {
                this.$Progress.start();
                this.form.put('api/entries/' + this.form.id).then(() => {
                    swal.fire(
                            'Updated!',
                            'The entry\'s information is updated.',
                            'success',
                        ),
                        this.$Progress.finish();
                    $('#editEntry').modal('hide');
                    Fire.$emit('AfterCreated')
                }).catch(() => {
                    this.$Progress.fail();
                    $('#editProject').modal('hide');
                    swal.fire("Failed!", "There was something wrong.", "warning");
                });
            },
            editModal(entry) {
                this.editmode = true;
                this.form.reset();
                event.preventDefault();
                $('#editEntry').modal('show');
                this.form.fill(project);
            },
            deleteEntry(id) {
                event.preventDefault();
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    // Send request to the server
                    if (result.value) {
                        this.form.delete('api/entries/' + id).then(() => {
                            swal.fire(
                                    'Deleted!',
                                    'Entry has been deleted.',
                                    'success',
                                ),
                                Fire.$emit('AfterCreated')
                        }).catch(() => {
                            swal.fire("Failed!", "There was something wrong.", "warning");
                        });
                    }
                })
            },

            loadProjects() {
                axios.get("api/projects").then(({
                    data
                }) => (this.projects = data));
            },
            loadEntries() {
                axios
                    .get("api/entries/showPG")
                    .then(({
                        data
                    }) => (this.entries = data));
            },
            createEntry() {
                this.$Progress.start();
                this.form.post("api/entries").then(() => {
                    Fire.$emit('AfterCreated');
                    // $('#addNewProject').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'Entry created successfully'
                    });
                    this.$Progress.finish();
                }).catch(() => {
                    this.$Progress.fail();
                })


            }
        },
        created() {
            this.loadProjects();
            //this.loadActivities();
            this.loadEntries();
            Fire.$on('AfterCreated', () => {
                this.loadProjects();
                this.loadEntries();
            })

        }
    }

</script>
