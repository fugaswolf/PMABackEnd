<template>
    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar-check"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Time entries today</span>
                        <span class="info-box-number">{{todaysEntries}} <small>{{totalTodaysEntriesTime}}</small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-calendar-alt"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total time entries</span>
                        <span class="info-box-number">{{totalEntries}}  <small>{{totalEntriesTime}}</small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fa fa-shopping-cart"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total projects</span>
                        <span class="info-box-number">{{totalProjects}} <small> {{ totalProjectsSales | toCurrency }}</small></span>
                      
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total employees</span>
                        <span class="info-box-number">{{this.totalEmp}}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 mt-5">
                <div class="card">
                    <div class="card-header">Recent entries</div>

                    <div class="card-body  table-responsive p-0">
                        <table class="table table">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th v-if="showUserHead">User</th>
                                    <th>Project</th>
                                    <th>Activity</th>
                                    <th>Description</th>
                                    <th>Date Start</th>
                                    <th>Date End</th>
                                    <th>Duration</th>
                                </tr>
                                <tr v-for="entry in entries.data" :key="entry.id">
                                    <td>{{entry.id}}</td>
                                    <td v-if="entry.user !== undefined && setShowUserHeadToTrue()">{{entry.user.name}}
                                    </td>
                                    <td>{{entry.activity.project.name }}</td>
                                    <td>{{entry.activity.name }}</td>
                                    <td>{{entry.description}}</td>
                                    <td>{{entry.start_date}}</td>
                                    <td>{{entry.end_date}}</td>
                                    <td>{{entry.duration}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="card-footer">
                            <pagination :data="entries" @pagination-change-page="getResults"></pagination>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                showUserHead: false,
                shownActivities: [],
                entries: {},
                projects: {},
                totalEmp: '',
                totalProjects: '',
                totalProjectsSales: '',
                totalEntries: '',
                todaysEntries: '',
                totalTodaysEntriesTime: '',
                totalEntriesTime: '',
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
            setShowUserHeadToTrue() {
                return this.showUserHead = true;
            },
            activitySelected(event) {
                console.log(event.target.value);
                this.form.activity_id = event.target.value;
            },
            projectSelected(event) {
                const projectId = event.target.value;
                axios
                    .get('api/activitiesByProject/' + projectId)
                    .then(({
                        data
                    }) => (this.shownActivities = data));
            },
            getResults(page = 1) {
                axios.get('api/entries/showAll?page=' + page)
                    .then(response => {
                        this.entries = response.data;
                    });
            },
            loadProjects() {
                axios.get("api/projects").then(({
                    data
                }) => (this.projects = data));
            },
            loadEntries() {
                axios
                    .get("api/entries/showAll")
                    .then(({
                        data
                    }) => (this.entries = data));
            },
            getTotalEmp() {
                axios
                    .get("api/reports/totalEmployees")
                    .then(({
                        data
                    }) => (this.totalEmp = data));
            },
            getTotalProjects() {
                axios
                    .get("api/reports/totalProjects")
                    .then(({
                        data
                    }) => (this.totalProjects = data));
            },
            getTotalProjectsSales() {
                axios
                    .get("api/reports/totalProjectsSales")
                    .then(({
                        data
                    }) => (this.totalProjectsSales = data));
            },
            getTotalEntries() {
                axios
                    .get("api/reports/totalEntries")
                    .then(({
                        data
                    }) => (this.totalEntries = data));
            },
            getTodaysEntries() {
                axios
                    .get("api/reports/totalTodaysEntries")
                    .then(({
                        data
                    }) => (this.todaysEntries = data));
            },
            getTodaysEntriesTime() {
                axios
                    .get("api/reports/totalTodaysEntriesTime")
                    .then(({
                        data
                    }) => (this.totalTodaysEntriesTime = data));
            },
            getTotalEntriesTime() {
                axios
                    .get("api/reports/totalEntriesTime")
                    .then(({
                        data
                    }) => (this.totalEntriesTime = data));
            },

        },
        created() {
            this.loadProjects();
            this.loadEntries();
            this.getTodaysEntries();
            this.getTotalEmp();
            this.getTotalEntries();
            this.getTotalProjects();
            this.getTotalProjectsSales();
            this.getTodaysEntriesTime();
            this.getTotalEntriesTime();
        },
    }

</script>
