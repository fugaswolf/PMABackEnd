<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-5">


                <div class="card">
                    <div class="card-body">

                        <form @submit.prevent="createEntry">
                            <div class="form-row align-content-center">
                                <div class="col-sm-4">
                                    <input v-model="form.description" type="text" placeholder="What are you doing?"
                                        name="description" class="form-control mr-1"
                                        :class="{ 'is-invalid': form.errors.has('description') }">
                                </div>
                                <div class="col-xs-2">
                                    <!-- <select type="text" name="project" id="project"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('project') }">
                                        <option value="" selected>Choose project</option>
                                        <option v-for="project in projectsWithActivities.data" v-bind:value="project.id" :key="project.id">{{project.name}}</option>
                                    </select> -->
                                    <has-error :form="form" field="project"></has-error>
                                </div>
                                <div class="col-xs-2">
                                    <!-- <select v-model="form.activity_id" type="text" name="activity_id" id="activity_id"
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('activity_id') }"
                                        place>
                                        <option value="" selected>Choose an activity</option>
                                        <option v-for="activity in project.activities" :key="activity.id" v-bind:value="activity.id">{{activity.name}}</option>
                                    </select> -->
                                    <has-error :form="form" field="activity_id"></has-error>
                                </div>

                                <div class="col-xs-0">
                                    <label>Start</label>
                                    <input type="datetime-local" v-model="form.dateStart" class="form-control"
                                        name="dateStart" id="meeting-time"
                                        :class="{ 'is-invalid': form.errors.has('dateStart') }">
                                </div>
                                <div class="col-xs-0">
                                    <label class="form-inline">End</label>
                                    <input type="datetime-local" v-model="form.dateEnd" class="form-control"
                                        name="dateEnd" id="meeting-time"
                                        :class="{ 'is-invalid': form.errors.has('dateEnd') }">
                                </div>
                                <div class="col-xs-1">
                                    <button type="button" class="btn btn-primary">Add entry</button>
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
                                    <th>Description</th>
                                    <th>Project</th>
                                    <th>Activity</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Duration</th>
                                    <th>Modify</th>
                                </tr>
                                <tr v-for="project in projects.data" :key="project.id">
                                    <td>{{project.id}}</td>
                                    <td>{{project.customer.name}}</td>
                                    <td>{{project.name}}</td>
                                    <td>{{project.comment}}</td>
                                    <td>{{project.budget}}</td>
                                    <td>{{project.status}}</td>
                                    <td>
                                        <a href="" @click="editModal(project)">
                                            <i class="fa fa-pencil-alt blue"></i>
                                        </a>
                                        /
                                        <a href="" @click="deleteProject(project.id)">
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
        <div class="modal fade" id="editProject" tabindex="-1" role="dialog" aria-labelledby="editProject"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProject">Edit entry</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="updateEntry">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Projects</label>
                                <select type="text" name="customer_id" id="customer_id"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('customer_id') }">
                                    <option value="">Choose a project</option>
                                    <option v-for="project in projectsWithActivities.data" v-bind:value="project.id"
                                        :key="project.id">{{project.name}}</option>
                                </select>
                                <has-error :form="form" field="customer_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Activities</label>
                                <!-- <select v-model="form.activity_id" type="text" name="customer_id" id="customer_id"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('customer_id') }">
                                    <option value="">Choose a project</option>
                                    <option v-for="activity in project.activities" :key="activity.id" v-bind:value="activity.id">{{project.name}}</option>
                                </select> -->
                                <has-error :form="form" field="customer_id"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea v-model="form.comment" type="text" name="comment" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('comment') }"></textarea>

                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select v-model="form.status" type="text" name="status" id="status" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('status') }">
                                    <option value="">Choose a status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                                <has-error :form="form" field="status"></has-error>
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
                editmode: false,
                entries: {},
                projects: {},
                projectsWithActivities: {},
                form: new Form({
                    id: '',
                    user_id: '',
                    activity_id: '',
                    dateStart: '',
                    dateEnd: '',
                    duration: '',
                    description: '',
                    remember: false
                })
            }
        },
        methods: {
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
            editModal(project) {
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
            loadActivities() {
                axios
                    .get("api/projectsWithActivities")
                    .then(({
                        data
                    }) => (this.projectsWithActivities = data));
            },
             loadEntries() {
                axios
                    .get("api/entries")
                    .then(({
                        data
                    }) => (this.entries = data));
            },
            createEntry() {
                this.$Progress.start();
                // Submit the form via a POST request
                this.form.post("api/entries/create").then(() => {
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
            this.loadEntries();
            Fire.$on('AfterCreated', () => {
                this.loadProjects();
            })

        }
    }

</script>
