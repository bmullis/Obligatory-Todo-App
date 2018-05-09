<template>
    <div class="dashboard-tasks">
        <transition name="fade" mode="out-in">
            <div v-if="message" class="row">
                <div class="col-md-12">
                    <div class="alert alert-success">
                        {{ message }}
                    </div>
                </div>
            </div>
        </transition>

        <div class="main-content row">
            <div class="col-md-4">
                <div v-if="loading" class="">
                    <div class="my-5 py-5 text-center">
                        <i class="fa fa-spinner fa-spin fa-3x fa-fw py-5"></i>
                    </div>
                </div>
                <div v-if="!loading">
                    <h3>In Progress</h3>
                    <div v-for="task in unfinishedTasks" class="card unfinished-card">
                        <div class="card-header">
                            <h5>{{ task.title }}</h5>
                        </div>
                        <div class="card-body">
                            {{ task.description }}
                        </div>
                        <div class="card-footer">
                            <p>{{ getDate(task.created_at) }}</p>
                            <div class="footer-buttons">
                                <i @click="deleteTask(task.id)" class="fa fa-check"></i>
                                <i class="fa fa-pencil"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <h3>Completed</h3>
                <div v-for="task in finishedTasks" class="card finished-card">
                    <div class="card-header">
                        <h5>{{ task.title }}</h5>
                    </div>
                    <div class="card-body">
                        {{ task.description }}
                    </div>
                    <div class="card-footer">
                        <p>Finished {{ getDate(task.completed_at) }}</p>
                        <div class="footer-buttons">
                            <i @click="deleteTask(task.id)" class="fa fa-trash"></i>
                            <i class="fa fa-pencil"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <h3>Create a New Task</h3>
                <div class="task-form">
                    <div class="card">
                        <form class="form" @submit.prevent="submitForm">
                            <div class="card-body">
                                <input class="form-control my-2" type="text" name="title" v-model="title" placeholder="Title of Task">
                                <textarea class="form-control my-2" name="description" v-model="description" placeholder="Description of Task"></textarea>
                            </div>
                            <div class="card-footer">
                                <input class="btn btn-md btn-primary" type="submit" name="" value="Create Task">
                            </div>
                        </form>
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
                loading: true,
                tasks: {},
                title: '',
                description: '',
                message: ''
            }
        },
        methods: {
            getTasks: function() {
                let self = this;

                axios.get('/tasks')
                    .then(function (response) {
                        console.log(response);
                        self.tasks = response.data;
                        self.loading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            submitForm: function() {
                let self = this;

                axios.post('/tasks/store', {
                    title: this.title,
                    description: this.description
                })
                .then(function (response) {
                    console.log(response);
                    self.title = '';
                    self.description = '';
                    self.getTasks();
                    self.message = response.data.message;
                    setTimeout(function(){ self.message = ''; }, 1500);

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            deleteTask: function(id) {
                let self = this;

                axios.put('/tasks/delete/' + id, {
                    deleted_at: moment()
                })
                .then(function (response) {
                    console.log(response);
                    self.getTasks();
                    self.message = response.data.message;
                    setTimeout(function(){ self.message = ''; }, 1500);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDate: function(date) {
                let momentDate = moment.utc(date);
                let currDate = moment();
                return momentDate.from(currDate);
            }
        },
        computed: {
            unfinishedTasks: function() {
                return this.tasks.filter(function(task) {
                    return !task.completed_at;
                });
            },
            finishedTasks: function() {
                return this.tasks.filter(function(task) {
                    return task.completed_at;
                });
            }
        },
        mounted: function() {
            this.getTasks();
        }
    }
</script>

<style>
    .main-content {
        padding-top: 4.25em;
        padding-bottom: 6em;
    }
    .card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.09);
        margin-bottom: 40px;
    }
    .alert{
        position: absolute;
        top: 10px;
        width: 100%;
    }
    .fade-enter-active, .fade-leave-active {
      transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to {
      opacity: 0;
    }
    .card-header {
        background-color: #ffffff;
        border: none;
    }
    .card-header h5 {
        margin: 0;
    }
    .card-footer {
        background-color: #ffffff;
        border: none;
    }
    .card-footer p {
        float: left;
        margin: 0;
        padding-top: 6px;
    }
    .footer-buttons {
        float: right;
    }
    .footer-buttons i {
        color: #ffffff;
        padding: 10px;
    }
    .finished-card .footer-buttons i:first-of-type {
        background-color: red;
    }
    .unfinished-card .footer-buttons i:first-of-type {
        background-color: green;
    }
    .footer-buttons i:nth-of-type(2) {
        background-color: blue;
    }
    .footer-buttons i:hover {
        cursor: pointer;
    }
</style>
