<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Activities</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <div v-for="project in projectsWithActivities.data" :key="project.id" class="col-md-12 mt-3">
                            <div class="card card-info card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">Project: {{project.name}}</h3>

                                    <div class="card-tools">
                                        <button class="btn btn-success" @click="newModal(project.id)">
                                            Add activity
                                            <i class="fa fa-tasks fa-fw"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-widget="collapse">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <!-- /.card-tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body" style="display: block;">
                                    <table class="table table">
                                        <tbody>
                                            <tr>
                                                <th>Name</th>
                                                <th>Comment</th>
                                                <th>Status</th>
                                                <th>Modify</th>
                                            </tr>
                                            <tr v-for="activity in project.activities" :key="activity.id">
                                                <td>{{activity.name}}</td>
                                                <td>{{activity.comment}}</td>
                                                <td v-if="activity.status === 1">
                                                    Active
                                                </td>
                                                <td v-else>
                                                    Inactive
                                                </td>
                                                <td>
                                                    <a href @click="editModal(activity)">
                                                        <i class="fa fa-pencil-alt blue"></i>
                                                    </a>
                                                    /
                                                    <a href @click="deleteActivity(activity.id)">
                                                        <i class="fa fa-trash-alt red"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <!--  -->
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="projectsWithActivities" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNewActivity" tabindex="-1" role="dialog" aria-labelledby="addNewActivity"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="editmode" id="addNewActivity">Edit activity</h5>
                        <h5 class="modal-title" v-show="!editmode" id="addNewActivity">Add a new activity</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="editmode ? updateActivity() : createActivity()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input v-model="form.name" type="text" name="name" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('name') }">
                            </div>
                            <div class="form-group">
                                <label>Comment</label>
                                <textarea v-model="form.comment" type="text" name="comment" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('comment') }"></textarea>
                            </div>

                            <div class="form-group" v-if="editmode === true">
                                <label>Status</label>
                                <select v-model="form.status" type="text" name="status" id="status" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('status') }">
                                    <option value>Choose a status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                                <has-error :form="form" field="status"></has-error>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Create activity</button>
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
                //selectedProjectId: null,
                editmode: false,
                projects: {},
                projectsWithActivities: {},
                form: new Form({
                    id: '',
                    project_id: '',
                    name: '',
                    comment: '',
                    status: '',
                    remember: false
                })
            };
        },
        methods: {
            getResults(page = 1) {
                axios.get("api/projectsWithActivities?page=" + page).then(response => {
                    this.projectsWithActivities = response.data;
                });
            },
            updateActivity(id) {
                this.$Progress.start();
                this.form
                    .put("api/activities/" + this.form.id)
                    .then(() => {
                        swal.fire(
                                "Updated!",
                                "The activity's information is updated.",
                                "success"
                            ),
                            this.$Progress.finish();
                        $("#addNewActivity").modal("hide");
                        Fire.$emit("AfterCreated");
                    })
                    .catch(() => {
                        this.$Progress.fail();
                        $("#addNewActivity").modal("hide");
                        swal.fire("Failed!", "There was something wrong.", "warning");
                    });
            },
            editModal(activity) {
                this.editmode = true;
                this.form.reset();
                event.preventDefault();
                $("#addNewActivity").modal("show");
                this.form.fill(activity);
            },
            newModal(projectId) {
                // normaal gezien een van de twee onderste zou moeten werken 
                // ik heb geen idee waarom het niet werkt

                // oke wtf ik zie het al XD

                this.form.project_id = projectId;
                this.form.fill({
                    project_id: projectId
                });

                this.editmode = false;
                // this.form.reset();
                $("#addNewActivity").modal("show");
            },
            deleteActivity(id) {
                event.preventDefault();
                swal
                    .fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    })
                    .then(result => {
                        // Send request to the server
                        if (result.value) {
                            this.form
                                .delete("api/activities/" + id)
                                .then(() => {
                                    swal.fire("Deleted!", "Activity has been deleted.", "success"),
                                        Fire.$emit("AfterCreated");
                                })
                                .catch(() => {
                                    swal.fire("Failed!", "There was something wrong.", "warning");
                                });
                        }
                    });
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
                // axios.get("api/customers").then(response => this.customers = response.data);
            },
            createActivity() {
                this.$Progress.start();
                // Submit the form via a POST request
                this.form.status = '1';
                this.form
                    .post("api/activities/create")
                    .then(() => {
                        Fire.$emit("AfterCreated");
                        $("#addNewActivity").modal("hide");
                        toast.fire({
                            type: "success",
                            title: "Activity created successfully"
                        });
                        this.$Progress.finish();

                        // als het goed gaat, (dus de activity is aangemaakt) dan pas form reset doen?
                        this.form.reset();

                        // en als ge da modal sluit ook ? ja
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
            }
        },
        created() {
            this.loadProjects();
            this.loadActivities();
            Fire.$on("AfterCreated", () => {
                this.loadActivities();
            });
        }
    };

</script>
