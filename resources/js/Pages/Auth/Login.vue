<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Login</title>
    </Head>

    <div class="d-flex align-items-center justify-content-center py-5"
        :class="{ 'has-background': $page.props.setting.authentication_background }"
        :style="{ backgroundImage: $page.props.setting.authentication_background ? `url('/storage/upload_files/settings/${$page.props.setting.authentication_background}')` : '' }">
        <div class="container-fluid">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                <div class="col mx-auto">
                    <div class="card mb-0">
                        <div class="card-body" :style="{ background: $page.props.setting.login_page_background }">
                            <div class="p-3">
                                <div class="text-center">
                                    <img v-if="$page.props.setting && $page.props.setting.logo" v-bind:src="'/storage/upload_files/settings/' + $page.props.setting.logo" style="max-width: 100%; height: 100px;"/>
                                </div>
                                <div class="mb-3">
                                    <h5 class="">Login</h5>
                                    <p class="mb-0">Silakan login dengan email dan password yang sudah anda daftarkan.</p>
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
                                        <div class="col-12">
                                            <label class="form-label">{{ loginTitle  }}</label>
                                            <input type="text" v-model="form.email" :class="{ 'is-invalid': errors.email }" class="form-control" :placeholder="loginPlaceholder">
                                            <div v-if="errors.email" class="invalid-feedback">
                                                {{ errors.email }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</label>
                                            <div class="input-group">
                                                <input v-if="showPassword" type="text" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">
                                                <input v-else type="password" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">

                                                <span class="input-group-text" @click="toggleShow"><i :class="{ 'bx bx-show': showPassword, 'bx bx-hide': !showPassword }"></i></span>
                                                <div v-if="errors.password" class="invalid-feedback">
                                                    {{ errors.password }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Login</button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-center ">
                                                <div v-if="$page.props.setting.add_user_registration == 1">Belum punya akun ? <Link href="/register">Daftar Disini</Link><br><br></div>
                                                Lupa {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }} ? <Link href="/user/forgot-password">Klik Disini</Link><br><br>
                                                <a :href="`https://wa.me/${$page.props.setting.whatsapp_number}?text=${encodeURI('Hallo, Admin. saya ingin bertanya....')}`" target="_blank">Klik Untuk Menghubungi Admin</a>
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

<style>
    .justify-content-center {
        justify-content: center;
        align-items: center;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        height: auto;
    }

    .justify-content-center.has-background::before {
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
    import LayoutAuth from '../../Layouts/Auth.vue';

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
                Inertia.post('/login', {
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
        },
        computed: {
            loginTitle() {
                const loginTypes = this.$page.props.setting.login_type;
                const authenticationField = this.$page.props.setting.authentication_field;

                const getTranslation = (type) => {
                    const field = authenticationField.find(field => field.name === type);
                    return field ? field.translate : type; // Jika tidak ditemukan, gunakan nama `type`
                };

                if (loginTypes.length === 3) {
                    return `${getTranslation(loginTypes[0].type)} / ${getTranslation(loginTypes[1].type)} / ${getTranslation(loginTypes[2].type)}`;
                } else if (loginTypes.length === 2) {
                    return `${getTranslation(loginTypes[0].type)} / ${getTranslation(loginTypes[1].type)}`;
                } else if (loginTypes.length === 1) {
                    return getTranslation(loginTypes[0].type);
                }
                return "";
            },
            loginPlaceholder() {
                const loginTypes = this.$page.props.setting.login_type;
                const authenticationField = this.$page.props.setting.authentication_field;

                const getTranslation = (type) => {
                    const field = authenticationField.find(field => field.name === type);
                    return field ? field.translate : type; 
                };

                if (loginTypes.length === 3) {
                    return `${getTranslation(loginTypes[0].type)} / ${getTranslation(loginTypes[1].type)} / ${getTranslation(loginTypes[2].type)}`;
                } else if (loginTypes.length === 2) {
                    return `${getTranslation(loginTypes[0].type)} / ${getTranslation(loginTypes[1].type)}`;
                } else if (loginTypes.length === 1) {
                    return getTranslation(loginTypes[0].type);
                }
                return "Login";
            }
        }
    }
</script>
