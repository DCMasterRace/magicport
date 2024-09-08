<template>
    <div col='12'>
        <div v-if="items.length">
        <table class="table table-striped-columns">
            <thead>
                <tr>
                    <th scope="col">Task Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(ind, key) in items" :key="key">
                    <td>{{ ind.name }}</td>
                    <td>{{ ind.description }}</td>
                    <td>{{ ind.status }}</td>
                    <td>
                        <a href="#" @click="editTask(ind)">Edit</a> 
                        | 
                        <a href="#" @click="openDeleteModal(ind)">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
        <div v-else>
            <span> There's no task for this project yet </span>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#projectModal" ref="openTaskModal">Add New Task</button>
        &nbsp;
        <button type="button" class="btn btn-primary" @click="backTo">Back to Projects</button>
        <button hidden type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deleteModal" ref="deleteModal">Delete</button>
    </div>


    <div ref="projectModal" class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Task</h5>
                    <button id="close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ref="cancel1Btn"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Task Name</label>
                            <input type="text" class="form-control" id="recipient-name" v-model="name">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Description:</label>
                            <textarea class="form-control" id="message-text" v-model="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Status</label>
                            <select class="form-control" v-model="status">
                                <option disabled value="">Please select one</option>
                                <option>todo</option>
                                <option>in-progress</option>
                                <option>done</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button hidden type="button" class="btn btn-danger" data-bs-dismiss="modal" ref="cancel1Btn">Cancel</button>
                    <button type="button" class="btn btn-danger" @click="closeModal()">Cancel</button>
                    <button type="button" class="btn btn-success" @click="saveTask()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>{{ name }} </span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" ref="cancel2Btn">Cancel</button>
                    <button type="button" class="btn btn-danger" @click="deleteProject()">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import { ref } from 'vue';
    export default {
        name: 'ProjectPage',
        data() {
            return {
                items: [],
                id: '',
                task_id: '',
                name: '',
                description: '',
                status: '',
                edit: false
            }
        },
        created() {
            if(!localStorage.getItem('token') != "" && !localStorage.getItem('token') != null){
                this.$router.push('/login');
            }
            this.id = this.$route.params.id;
            this.getItems();
        },
        methods: {
            async getItems() {
                this.isSubmitting = true
                fetch('/sanctum/csrf-cookie').
                then((res) => {
                    console.log(res);
                });
                const result = await fetch(import.meta.env.VITE_API_URL + "projects/" + this.id +'/tasks', {
                    method: "GET",
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ` + localStorage.getItem('token')
                    },
                })
                    .then(res => res.json())
                    .then((json) => {
                        this.items = json;
                    });
            },
            async saveTask() {
                let url = '';
                let method = '';
                if(!this.edit) {
                    url = import.meta.env.VITE_API_URL + "projects/" + this.id + "/tasks";
                    method = "POST";
                } else {
                    url = import.meta.env.VITE_API_URL + "projects/" + this.id + "/tasks/" + this.task_id;
                    method = "PUT";
                }
                let payload = {
                    name: this.name,
                    description: this.description,
                    status: this.status
                }
                const result = await fetch(url, {
                    method: method,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ` + localStorage.getItem('token')
                    },
                    body: JSON.stringify(payload)
                })
                    .then(res => res.json())
                    .then((json) => {
                        this.name = '';
                        this.description = '';
                        this.status = '';
                        this.$refs.cancel1Btn.click();
                        this.edit = false;
                        this.getItems();
                    });
            },
            async editTask(data) {
                this.edit = true;
                this.task_id = data.id;
                this.name = data.name;
                this.description = data.description;
                this.status = data.status;
                this.$refs.openTaskModal.click();

            },
            async closeModal() {
                this.name = "";
                this.description = "";
                this.$refs.cancel1Btn.click();
            },
            async openDeleteModal(ind) {
                this.task_id = ind.id;
                this.name = ind.name;
                this.description = ind.description;
                this.$refs.deleteModal.click();
            },
            async deleteProject() {
                const result = await fetch(import.meta.env.VITE_API_URL + "projects/" + this.id + "/tasks/" + this.task_id, {
                    method: "DELETE",
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ` + localStorage.getItem('token')
                    },
                })
                    .then(res => res.json())
                    .then((json) => {
                        this.name = '';
                        this.description = '';
                        this.$refs.cancel2Btn.click();
                        this.getItems();
                    });
            },
            async openTask(data) {
                this.$router.push('/projects/'+data.id+'/task');
            },
            async backTo() {
                this.$router.push('/projects');
            }
        }
    }
</script>
