<template>
    <div class="about">
        <div class="row justify-content-md-center mt-5">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Sign In</h5>
                        <form>
                            <div class="mb-3">
                                <label 
                                 htmlFor="email"
                                 class="form-label">
                                    Email address
                                </label>
                                <input 
                                 v-model="email"
                                 type="email"
                                 class="form-control"
                                 id="email"
                                 name="email"
                                 />
                            </div>
                            <div class="mb-3">
                                <label 
                                 htmlFor="password"
                                 class="form-label">Password
                                </label>
                                <input 
                                 v-model="password"
                                 type="password"
                                 class="form-control"
                                 id="password"
                                 name="password"
                                 />
                            </div>
                            <div class="d-grid gap-2">
                                <button 
                                 :disabled="isSubmitting"
                                 @click="login()"
                                 type="button"
                                 class="btn btn-primary btn-block">Login</button>
                                <p class="text-center">Don't have account? 
                                <router-link to="/register">Register here </router-link>
                                </p>
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
        name: 'LoginPage',
        setup() {
            return {
                email:'',
                password:'',
                validationErrors:{},
                isSubmitting:false,
            }
        },
        created() {
            if(localStorage.getItem('token') != "" && localStorage.getItem('token') != null){
                this.$router.push('/projects');
            }
        },
        methods: {
            async login() {
                this.isSubmitting = true
                let payload = {
                    email: this.email,
                    password: this.password,
                }
                fetch('/sanctum/csrf-cookie');
                const result = await fetch(import.meta.env.VITE_API_URL + "login", {
                    method: "POST",
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload)
                })
                    .then(res => res.json())
                    .then((json) => {
                        localStorage.setItem('token', json.data.token)
                        this.$router.push('/projects')
                    });
            }
        }
    }
</script>
