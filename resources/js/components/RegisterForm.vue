<template>
    <div class="container" id="register">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register</div>

                    <div class="card-body">
                        <form method="POST" @submit="register()">
                            <input type="hidden" name="_token" :value="csrf">

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                <div class="col-md-6">
                                    <input v-model="name" id="name" type="text" class="form-control" :class="{ 'is-invalid' : 'name' in errors}" name="name" value="" required autocomplete="name" autofocus>
                                    <span v-if="errors['name']" class="invalid-feedback" role="alert">
                                        <strong>{{ errors['name'][0] }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input v-model="email" id="email" type="email" class="form-control" :class="{ 'is-invalid' : 'email' in errors}" name="email" value="" required autocomplete="email">
                                    <span v-if="errors['email']" class="invalid-feedback" role="alert">
                                        <strong>{{ errors['email'][0] }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input v-model="password" id="password" type="password" class="form-control" :class="{ 'is-invalid' : 'password' in errors}" name="password" required autocomplete="new-password">
                                    <span v-if="errors['password']" class="invalid-feedback" role="alert">
                                        <strong>{{ errors['password'][0] }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm password</label>

                                <div class="col-md-6">
                                    <input v-model="passwordConfirm" id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Register</button>
                                </div>
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
        name : 'RegisterForm',
        props : ['csrf', 'registerRoute'],
        data : function () {
            return  {
                name : 'test',
                email : 'test@test.com',
                password : 'testas123',
                passwordConfirm : 'testas123',
                errors : {},
            }
        },
        methods : {
            register() {
                event.preventDefault();
                this.$emit('toggle-load-overlay', true);
                fetch(this.registerRoute, {
                    method : 'POST',
                    body : JSON.stringify({
                        name : this.name,
                        email : this.email,
                        password : this.password,
                        password_confirmation : this.passwordConfirm
                    }),
                    headers : {
                        "Content-Type": "application/json; charset=utf-8",
                        'X-CSRF-TOKEN' : this.csrf,
                    },
                })
                .then(data => data.json())
                .then(data => {
                    if(!data['success'])
                        this.errors = data['errors'];
                    else {
                        this.$emit('registered', data['user']);
                    }
                    this.$emit('toggle-load-overlay', false);
                })
                .catch(error => {
                    this.$emit('toggle-load-overlay', false);
                    console.log(error);
                });
            }
        }
    }
</script>
