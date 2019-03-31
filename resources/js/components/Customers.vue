<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Customers</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal">Add customer <i
                                    class="fa fa-user-plus fa-fw"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Division</th>
                                    <th>Name</th>
                                    <th>Comment</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Registered at</th>
                                    <th>Modify</th>
                                </tr>
                                <tr v-for="customer in customers.data" :key="customer.id">
                                    <td>{{customer.id}}</td>
                                    <td>{{customer.division_id}}</td>
                                    <td>{{customer.name}}</td>
                                    <td>{{customer.comment}}</td>
                                    <td>{{customer.address}}</td>
                                    <td>{{customer.phone}}</td>
                                    <td>{{customer.email}}</td>
                                    <td v-if="customer.status === 1">
                                        Active
                                    </td>
                                    <td v-else>
                                        Inactive
                                    </td>
                                    <td>{{customer.created_at | myDate}}</td>
                                    <td>
                                        <a href="" @click="editModal(customer)">
                                            <i class="fa fa-pencil-alt blue"></i>
                                        </a>
                                        /
                                        <a href="" @click="deleteCustomer(customer.id)">
                                            <i class="fa fa-trash-alt red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="customers" @pagination-change-page="getResults"></pagination>
                    </div>

                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNewCustomer" tabindex="-1" role="dialog" aria-labelledby="addNewCustomer"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="editmode" id="addNewCustomer">Edit customer</h5>
                        <h5 class="modal-title" v-show="!editmode" id="addNewCustomer">Add a new customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form @submit.prevent="editmode ? updateCustomer() : createCustomer()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Division ID</label>
                                <select v-model="form.division_id" type="text" name="division_id" id="division_id"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('division_id') }">
                                    <option value="">Choose a division</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="0">0</option>
                                </select>
                                <has-error :form="form" field="division_id"></has-error>
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
                                <label>Country</label>
                                <input v-model="form.country" type="text" name="country" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('country') }">

                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input v-model="form.address" type="text" name="address" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('address') }">

                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input v-model="form.phone" type="number" name="phone" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('phone') }">

                            </div>

                            <div class="form-group">
                                <label>Email address</label>
                                <input v-model="form.email" type="email" name="email" class="form-control"
                                    :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
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
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Create customer</button>
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
                customers: {},
                form: new Form({
                    id: '',
                    division_id: '',
                    name: '',
                    comment: '',
                    country: '',
                    address: '',
                    phone: '',
                    email: '',
                    status: '',
                    remember: false
                })
            }
        },
        methods: {
            getResults(page = 1) {
                axios.get('api/customers?page=' + page)
                    .then(response => {
                        this.customers = response.data;
                    });
            },
            updateCustomer(id) {
                this.$Progress.start();
                this.form.put('api/customers/' + this.form.id).then(() => {
                    swal.fire(
                            'Updated!',
                            'The customers information is updated.',
                            'success',
                        ),
                        this.$Progress.finish();
                    $('#addNewCustomer').modal('hide');
                    Fire.$emit('AfterCreated')
                }).catch(() => {
                    this.$Progress.fail();
                    $('#addNewCustomer').modal('hide');
                    swal.fire("Failed!", "There was something wrong.", "warning");
                });
            },
            editModal(customer) {
                this.editmode = true;
                this.form.reset();
                event.preventDefault();
                $('#addNewCustomer').modal('show');
                this.form.fill(customer);
            },
            newModal() {
                this.editmode = false;
                this.form.reset();
                $('#addNewCustomer').modal('show');
            },
            deleteCustomer(id) {
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
                        this.form.delete('api/customers/' + id).then(() => {
                            swal.fire(
                                    'Deleted!',
                                    'Customer has been deleted.',
                                    'success',
                                ),
                                Fire.$emit('AfterCreated')
                        }).catch(() => {
                            swal.fire("Failed!", "There was something wrong.", "warning");
                        });
                    }
                })
            },

            loadCustomers() {
                axios.get("api/customers").then(({
                    data
                }) => (this.customers = data));
            },
            createCustomer() {
                this.$Progress.start();
                // Submit the form via a POST request
                this.form.post("api/customers/create").then(() => {
                    Fire.$emit('AfterCreated');
                    $('#addNewCustomer').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'Customer created successfully'
                    });
                    this.$Progress.finish();
                }).catch(() => {
                    this.$Progress.fail();
                })


            }
        },
        created() {
            this.loadCustomers();
            Fire.$on('AfterCreated', () => {
                this.loadCustomers();
            })

        }
    }

</script>
