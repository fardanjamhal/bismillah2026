<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Forgot Password</title>
    </Head>
    
    <div class="authentication-reset-password d-flex align-items-center justify-content-center"
        :class="{ 'has-background': $page.props.setting.authentication_background }"
        :style="{ backgroundImage: $page.props.setting.authentication_background ? `url('/storage/upload_files/settings/${$page.props.setting.authentication_background}')` : '' }">
        <div class="container-fluid">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                <div class="col mx-auto">
                    <div class="card mb-0">
                        <div class="card-body" :style="{ background: $page.props.setting.login_page_background }">
                            <div class="p-3">
                                <div class="mb-3">
                                    <h3 class="text-center">Reset {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</h3>
                                    <p class="mt-3 text-center">Silakan masukkan {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }} baru Anda!</p>
                                </div>
                                <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                                    <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.error"></div>
                                </div>
                                <div v-if="$page.props.session.warning" class="alert alert-warning border-0">
                                    <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.warning"></div>
                                </div>
                                <div v-if="$page.props.session.success" class="alert alert-success border-0">
                                    <i class="fa fa-exclamation-triangle"></i> <div v-html="$page.props.session.success"></div>
                                </div>
                                <div class="form-body">
                                    <form @submit.prevent="submit" class="row g-3">
                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'email' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'email')?.translate }}</label>
                                            <input type="email" v-model="form.email" :class="{ 'is-invalid': errors.email }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'email')?.translate" readonly>
                                            <div v-if="errors.email" class="invalid-feedback">
                                                {{ errors.email }}
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'password' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }} Baru</label>
                                            <div class="input-group">
                                                <input v-if="showPassword" type="text" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">
                                                <input v-else type="password" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">
                                                <span class="input-group-text" @click="toggleShowPassword"><i :class="{ 'bx bx-show': showPassword, 'bx bx-hide': !showPassword }"></i></span>     
                                                <div v-if="errors.password" class="invalid-feedback">
                                                    {{ errors.password }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'password' && field.is_active == '1')">
                                            <label class="form-label">Konfirmasi {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }} Baru</label>
                                            <div class="input-group">
                                                <input v-if="showPasswordConfirmation" type="text" v-model="form.password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }" class="form-control" :placeholder="'Konfirmasi ' + $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">
                                                <input v-else type="password" v-model="form.password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }" class="form-control" :placeholder="'Konfirmasi ' + $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">
                                                <span class="input-group-text" @click="toggleShowPasswordConfirmation"><i :class="{ 'bx bx-show': showPasswordConfirmation, 'bx bx-hide': !showPasswordConfirmation }"></i></span>     
                                                <div v-if="errors.password_confirmation" class="invalid-feedback">
                                                    {{ errors.password_confirmation }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Ubah {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</button>
                                                <Link href="/login" class="btn btn-light mt-2"><i class="bx bx-arrow-back mr-1"></i>Kembali ke Login</Link>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            <!--end row-->
        </div>
    </div>
</template>

<script>
    //import layout
    import LayoutAuth from '../../Layouts/Auth.vue';

    // import Head form Inertia
    import { Head } from '@inertiajs/inertia-vue3';

    //import reactive
    import { reactive } from 'vue';

    // import link
    import { Link } from '@inertiajs/inertia-vue3';

    import { ref } from 'vue';

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
            session: Object,
        },

        setup() {
            const form = reactive({
                token: ref("" || (new URL(document.location)).searchParams.get('token')),
                email: ref("" || (new URL(document.location)).searchParams.get('email')),
                password: '',
                password_confirmation: '',
            });

            const showPassword = ref(false);
            const showPasswordConfirmation = ref(false);

            const toggleShowPassword = () => {
                showPassword.value = !showPassword.value; // Update the value using .value with ref
            };

            const toggleShowPasswordConfirmation = () => {
                showPasswordConfirmation.value = !showPasswordConfirmation.value; 
            };

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post('/user/reset-password', {
                    // data
                    email: form.email,
                    token: form.token,
                    password: form.password,
                    password_confirmation: form.password_confirmation,
                });
            }

            // return form state and submit method
            return {
                form,
                submit,
                showPassword,
                toggleShowPassword,
                showPasswordConfirmation,
                toggleShowPasswordConfirmation,
            }
        }
    }
</script>
