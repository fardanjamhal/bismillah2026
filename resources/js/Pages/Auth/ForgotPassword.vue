<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Forgot {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</title>
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
                                    <p class="text-center">Kembali Ke Halaman <Link href="/login">Login</Link></p>
                                    <p class="mt-3">Pastikan kontak yang dimasukan adalah kontak yang telah didaftarkan.</p>
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
                                            <label class="form-label">
                                                {{ $page.props.setting.authentication_field.find(field => field.name == ($page.props.setting.notification_type == 1 ? 'phone_number' : 'email'))?.translate }}
                                            </label>
                                            <input type="text" v-model="form.contact" :class="{ 'is-invalid': errors.contact }" class="form-control" :placeholder="'Contoh: ' + ($page.props.setting.notification_type == 1 ? '6281212126043' : 'user@example.com')">
                                            <div v-if="errors.contact" class="invalid-feedback">
                                                {{ errors.contact }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Kirim Perubahan {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <p class="text-center">
                                                Kontak tidak terdaftar, 
                                                <a :href="`https://wa.me/${$page.props.setting.whatsapp_number}?text=${encodeURIComponent(`*[FORGOT PASSWORD - ${$page.props.setting.app_name}]*\n\nHallo, Admin saya sudah daftar dan ingin reset password, ketika konfirmasi kontak tidak terdaftar, berikut data saya untuk perbaikan:\n\nNama:\nEmail:\nNomor Handphone Aktif:\n\n terimakasih.`)}`" target="_blank">klik disini</a> 
                                                untuk pemulihan data.
                                            </p>
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
            user: Object,
        },

        setup() {
            const form = reactive({
                contact: '',
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post('/user/forgot-password', {
                    // data
                    contact: form.contact,
                });
            }

            // return form state and submit method
            return {
                form,
                submit,
            }
        }
    }
</script>
