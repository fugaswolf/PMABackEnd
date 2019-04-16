<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users list</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal">Add user <i class="fa fa-user-plus fa-fw"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Registered at</th>
                                    <th>Modify</th>
                                </tr>
                                <tr v-for="user in users.data" :key="user.id">
                                    <td>{{user.id}}</td>
                                    <td>{{user.name}}</td>
                                    <td>{{user.email}}</td>
                                    <td>{{user.roles.name}}</td>
                                    <td>{{user.created_at | myDate}}</td>
                                    <td>
                                        <a href="" @click="editModal(user)">
                                            <i class="fa fa-pencil-alt blue"></i>
                                        </a>
                                        /
                                        <a href="" @click="deleteUser(user.id)">
                                            <i class="fa fa-trash-alt red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="users" @pagination-change-page="getResults"></pagination>
                    </div>
                    
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNewUser" tabindex="-1" role="dialog" aria-labelledby="addNewUser" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="editmode" id="addNewUser">Edit user</h5>
                        <h5 class="modal-title" v-show="!editmode" id="addNewUser">Add a new user</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Role</label>
                                <select v-model="form.role" type="text" name="role" id="role"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('role') }">
                                    <option>Choose a role</option>
                                    <option value="admin">Admin</option>
                                    <option value="worker">Worker</option>
                                    <option value="manager">Manager</option>
                                    <option value="hr">HR</option>
                                    <option value="consultant">Consultant</option>
                                </select>
                                <has-error :form="form" field="role"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input v-model="form.name" type="text" name="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">

                            </div>

                            <div class="form-group">
                                <label>Email address</label>
                                <input v-model="form.email" type="email" name="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input v-model="form.password" type="password" name="password" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Create user</button>
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
                users: {},
                form: new Form({
                    id:'',
                    roles: '',
                    name: '',
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },
        methods: {
            getResults(page = 1){
                axios.get('api/users?page=' + page)
				.then(response => {
					this.users = response.data;
				});
            },
            updateUser(id){
                this.$Progress.start();
                this.form.put('api/users/'+this.form.id).then(() => {
                    swal.fire(
                                    'Updated!',
                                    'The user information is updated.',
                                    'success',
                                ),
                                this.$Progress.finish();
                                 $('#addNewUser').modal('hide');
                                Fire.$emit('AfterCreated')
                }).catch(() => {
                    this.$Progress.fail();
                     $('#addNewUser').modal('hide');
                    swal.fire("Failed!", "There was something wrong.", "warning");
                });
            },
            editModal(user){
                this.editmode = true;
                this.form.reset();
                event.preventDefault();
                $('#addNewUser').modal('show');
                this.form.fill(user);
                const role = this.getRoleName(user);

                //var element = document.getElementById(id);
                //element.value = valueToSelect;

                // https://stackoverflow.com/questions/43839066/how-can-i-set-selected-option-selected-in-vue-js-2
            },
            getRoleName(user) {
                return user.roles[0].name;
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNewUser').modal('show');
            },
            deleteUser(id) {
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
                        this.form.delete('api/users/' + id).then(() => {
                            swal.fire(
                                    'Deleted!',
                                    'User has been deleted.',
                                    'success',
                                ),
                                Fire.$emit('AfterCreated')
                        }).catch(() => {
                            swal.fire("Failed!", "There was something wrong.", "warning");
                        });
                    }
                })
            },

            loadUsers() {
                axios.get("api/users").then(({
                    data
                }) => (this.users = data));
            },
            createUser() {
                this.$Progress.start();
                // Submit the form via a POST request
                this.form.post("api/users/create").then(() => {
                    Fire.$emit('AfterCreated');
                    $('#addNewUser').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'User created successfully'
                    });
                    this.$Progress.finish();
                }).catch(() => {
                    this.$Progress.fail();
                })


            }
        },
        created() {
            this.loadUsers();
            Fire.$on('AfterCreated', () => {
                this.loadUsers();
            })

        }
    }

</script>
