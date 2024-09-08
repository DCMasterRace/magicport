<template>
    <div col='12'>
        <span style="font-size:6; color:red">* Click row to go to Project Task</span>
        <table class="table table-striped-columns">
            <thead>
                <tr>
                    <th scope="col">Project Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(ind, key) in items" :key="key">
                    <td @click="openTask(ind)">{{ ind.name }}</td>
                    <td @click="openTask(ind)">{{ ind.description }}</td>
                    <td>
                        <a href="#" @click.prevent="editProject(ind)">Edit</a> 
                        | 
                        <a href="#" @click.prevent="openDeleteModal(ind)">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#projectModal" ref="openModal">Add New Project</button>
        <button hidden type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deleteModal" ref="deleteModal">Delete</button>
    </div>


    <div ref="projectModal" class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Project</h5>
                    <button id="close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Project Name</label>
                            <input type="text" class="form-control" id="recipient-name" v-model="name">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Description:</label>
                            <textarea class="form-control" id="message-text" v-model="description"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button hidden type="button" class="btn btn-danger" data-bs-dismiss="modal" ref="cancel1Btn">Cancel</button>
                    <button type="button" class="btn btn-danger" @click="closeModal()">Cancel</button>
                    <button type="button" class="btn btn-success" @click="saveProject()">Save</button>
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
                name: '',
                description: '',
                edit: false
            }
        },
        created() {
            if(!localStorage.getItem('token') != "" && !localStorage.getItem('token') != null){
                this.$router.push('/login');
            }
            this.getItems();
        },
        methods: {
            async getItems() {
                this.isSubmitting = true
                fetch('/sanctum/csrf-cookie').
                then((res) => {
                    console.log(res);
                });
                const result = await fetch(import.meta.env.VITE_API_URL + "projects", {
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
            async saveProject() {
                let url = '';
                let method = '';
                if(!this.edit) {
                    url = import.meta.env.VITE_API_URL + "projects";
                    method = "POST";
                } else {
                    url = import.meta.env.VITE_API_URL + "projects/" + this.id;
                    method = "PUT";
                }
                console.log('method', method);
                console.log('url', url);
                let payload = {
                    name: this.name,
                    description: this.description,
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
                        this.$refs.cancel1Btn.click();
                        this.getItems();
                    });
            },
            async editProject(data) {
                this.edit = true;
                this.id = data.id;
                this.name = data.name;
                this.description = data.description;
                this.$refs.openModal.click();
            },
            async closeModal() {
                this.name = "";
                this.description = "";
                this.$refs.cancel1Btn.click();
            },
            async openDeleteModal(ind) {
                this.id = ind.id;
                this.name = ind.name;
                this.description = ind.description;
                this.$refs.deleteModal.click();
            },
            async deleteProject() {
                const result = await fetch(import.meta.env.VITE_API_URL + "projects/" + this.id, {
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
                this.$router.push('/projects/'+data.id+'/tasks');
            }
        }
    }
</script>
