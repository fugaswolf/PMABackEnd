<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-5">
                <form @submit.prevent="createActivity">
                    <div class="form-row">
                        <div class="col-7">
                            <input type="text" class="form-control" placeholder="What">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="State">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Zip">
                        </div>
                        <div class="form-group">
                            <div class="input-group date" id="datetimepicker9" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input"
                                    data-target="#datetimepicker9" />
                                <div class="input-group-append" data-target="#datetimepicker9"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form @submit.prevent="editmode ? updateProject() : createProject()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Customers</label>
                            <select v-model="form.customer_id" type="text" name="customer_id" id="customer_id"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('customer_id') }">
                                <option value="">Choose a customer</option>
                                <option v-for="customer in customers.data" v-bind:value="customer.id"
                                    :key="customer.id">{{customer.name}}</option>
                            </select>
                            <has-error :form="form" field="customer_id"></has-error>
                        </div>
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
                        <div class="form-group">
                            <label>Budget</label>
                            <input v-model="form.budget" type="number" name="budget" class="form-control"
                                :class="{ 'is-invalid': form.errors.has('budget') }">

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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Time entries</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal">Add project <i
                                    class="fa fa-folder-plus fa-fw"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Name</th>
                                    <th>Comment</th>
                                    <th>Budget</th>
                                    <th>Status</th>
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
                        <pagination :data="projects" @pagination-change-page="getResults"></pagination>
                    </div>

                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNewProject" tabindex="-1" role="dialog" aria-labelledby="addNewProject"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="editmode" id="addNewProject">Edit project</h5>
                        <h5 class="modal-title" v-show="!editmode" id="addNewProject">Add a new project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="editmode ? updateProject() : createProject()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Customers</label>
                                <select v-model="form.customer_id" type="text" name="customer_id" id="customer_id"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('customer_id') }">
                                    <option value="">Choose a customer</option>
                                    <option v-for="customer in customers.data" v-bind:value="customer.id"
                                        :key="customer.id">{{customer.name}}</option>
                                </select>
                                <has-error :form="form" field="customer_id"></has-error>
                            </div>
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
                            <div class="form-group">
                                <label>Budget</label>
                                <input v-model="form.budget" type="number" name="budget" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('budget') }">

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
                projects: {},
                customers: [],
                form: new Form({
                    id: '',
                    customer_id: '',
                    name: '',
                    comment: '',
                    budget: '',
                    status: '',
                    remember: false
                })
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get('api/projects?page=' + page)
                    .then(response => {
                        this.projects = response.data;
                    });
            },
            updateProject(id) {
                this.$Progress.start();
                this.form.put('api/projects/' + this.form.id).then(() => {
                    swal.fire(
                            'Updated!',
                            'The projects information is updated.',
                            'success',
                        ),
                        this.$Progress.finish();
                    $('#addNewProject').modal('hide');
                    Fire.$emit('AfterCreated')
                }).catch(() => {
                    this.$Progress.fail();
                    $('#addNewProject').modal('hide');
                    swal.fire("Failed!", "There was something wrong.", "warning");
                });
            },
            editModal(project) {
                this.editmode = true;
                this.form.reset();
                event.preventDefault();
                $('#addNewProject').modal('show');
                this.form.fill(project);
            },
            newModal() {
                this.editmode = false;
                this.form.reset();
                $('#addNewProject').modal('show');
            },
            deleteProject(id) {
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
                        this.form.delete('api/projects/' + id).then(() => {
                            swal.fire(
                                    'Deleted!',
                                    'Project has been deleted.',
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
            loadCustomers() {
                axios.get("api/customers").then(({
                    data
                }) => (this.customers = data));
                // axios.get("api/customers").then(response => this.customers = response.data);
            },
            createProject() {
                this.$Progress.start();
                // Submit the form via a POST request
                this.form.post("api/projects/create").then(() => {
                    Fire.$emit('AfterCreated');
                    $('#addNewProject').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'Project created successfully'
                    });
                    this.$Progress.finish();
                }).catch(() => {
                    this.$Progress.fail();
                })


            }
        },
        created() {
            this.loadProjects();
            this.loadCustomers();
            Fire.$on('AfterCreated', () => {
                this.loadProjects();
            })

        }
    }

</script>
