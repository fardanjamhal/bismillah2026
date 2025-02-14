<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Login</title>
    </Head>

    <div class="authentication-reset-password d-flex align-items-center justify-content-center"
        :class="{ 'has-background': $page.props.setting.authentication_background }"
        :style="{ backgroundImage: $page.props.setting.authentication_background ? `url('/storage/upload_files/settings/${$page.props.setting.authentication_background}')` : '' }">

        <div class="row">
            <div class="col-l2 col-lg-10 mx-auto">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-lg-12 border-end">
                            <div class="card-body mb-5">
                                <form @submit.prevent="submit" class="row g-5 mb-3">
                                    <div class="px-5">
                                        <div style="text-align:center;" v-if="$page.props.setting && $page.props.setting.logo">
                                            <img v-bind:src="'/storage/upload_files/settings/' + $page.props.setting.logo" style="max-width: 100%; height: 100px;"/>
                                        </div>
                                        <br>
                                        <h4 class="font-weight-bold">Login</h4>
                                        <p class="text-muted">Silakan login dengan email dan password yang sudah anda daftarkan.</p>
                                        <div class="mb-3 mt-0">
                                            <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                                                <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.error"></div>
                                            </div>

                                            <div v-if="$page.props.session.success" class="alert alert-success border-0">
                                                <i class="fa fa-exclamation-triangle"></i> <div v-html="$page.props.session.success"></div>
                                            </div>
                                        </div>
                                        <div class="mb-3 mt-0">
                                            <label class="form-label">Email / Username</label>
                                            <input type="text" v-model="form.email" :class="{ 'is-invalid': errors.email }" class="form-control" :placeholder="$page.props.setting.login_type == 3 ? 'Email / Username' : ($page.props.setting.login_type == 2 ? 'Username' : 'Email')">
                                            <div v-if="errors.email" class="invalid-feedback">
                                                {{ errors.email }}
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <div class="input-group">
                                                <input v-if="showPassword" type="text" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control" placeholder="Password">
                                                <input v-else type="password" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control" placeholder="Password">

                                                <span class="input-group-text" @click="toggleShow"><i :class="{ 'bx bx-show': showPassword, 'bx bx-hide': !showPassword }"></i></span>
                                                <div v-if="errors.password" class="invalid-feedback">
                                                    {{ errors.password }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">Login</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .authentication-reset-password {
        justify-content: center;
        align-items: center;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        height: auto;
    }

    .authentication-reset-password.has-background::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5));
    }
</style>

<script>
    //import layout
    import LayoutAuth from '../../../Layouts/Auth.vue';

    // import Head form Inertia
    import { Head } from '@inertiajs/inertia-vue3';

    //import reactive
    import { reactive } from 'vue';

    import { ref } from 'vue';

    // import link
    import { Link } from '@inertiajs/inertia-vue3';

    //import inertia adapter
    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutAuth,

        components: {
            Head,
            Link
        },

        //props
        props: {
            errors: Object,
            session: Object
        },

        setup() {
            const form = reactive({
                email: '',
                password: ''
            });

            const showPassword = ref(false);
            
            // submit method
            const submit = () => {
                // send data to server
                Inertia.post('/admin/login', {
                    // data
                    email: form.email,
                    password: form.password,
                });
            }

            const toggleShow = () => {
                showPassword.value = !showPassword.value; // Update the value using .value with ref
            };

            // return form state and submit method
            return {
                form,
                showPassword,
                toggleShow, 
                submit,
            }
        }
    }
</script>
