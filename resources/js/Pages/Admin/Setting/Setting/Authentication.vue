<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Setting</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Setting</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Setting</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-12">
                    <form @submit.prevent="submit">
                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-primary" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <Link href="/admin/settings/applications" class="nav-link">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Aplikasi</div>
                                            </div>
                                        </Link>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <Link href="/admin/settings/authentications" class="nav-link active">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Autentikasi</div>
                                            </div>
                                        </Link>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <Link href="/admin/settings/transactions" class="nav-link">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Transaksi</div>
                                            </div>
                                        </Link>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <Link href="/admin/settings/notifications" class="nav-link">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Notifikasi</div>
                                            </div>
                                        </Link>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <Link href="/admin/settings/site-configurations" class="nav-link">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Konfigurasi Situs</div>
                                            </div>
                                        </Link>
                                    </li>
                                    <li v-if="$page.props.setting.enable_affiliate_feature == 1" class="nav-item" role="presentation">
                                        <Link href="/admin/settings/affiliates" class="nav-link">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Program Afiliasi</div>
                                            </div>
                                        </Link>
                                    </li>
                                </ul>
                                <div class="tab-content py-3">
                                    <div class="tab-pane fade active show" role="tabpanel">
                                        <div class="row g-3">  
                                            <div class="col-12">
                                                <div v-if="$page.props.session.success" class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="font-35 text-white"><i class='bx bxs-check-circle'></i>
                                                        </div>
                                                        <div class="ms-3">
                                                            <h6 class="mb-0 text-white">Sukses</h6>
                                                            <div class="text-white">{{ $page.props.session.success }}</div>
                                                        </div>
                                                    </div>                   
                                                </div>

                                                <div v-if="$page.props.session.error" class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="font-35 text-white"><i class='bx bxs-message-square-x'></i>
                                                        </div>
                                                        <div class="ms-3">
                                                            <h6 class="mb-0 text-white">Gagal</h6>
                                                            <div class="text-white">{{ $page.props.session.error }}</div>
                                                        </div>
                                                    </div>                                  
                                                </div>

                                                <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                                                    <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                                                    <ul class="mt-3">
                                                        <li v-for="error in errors">{{ error }}</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Aktifkan Verifikasi Peserta</label>
                                                <select v-model="form.add_activation_user" :class="{ 'is-invalid': errors.add_activation_user }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                                <div v-if="errors.add_activation_user" class="invalid-feedback">
                                                    {{ errors.add_activation_user }}
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Aktifkan Form Registrasi Peserta</label>
                                                <select v-model="form.add_user_registration" :class="{ 'is-invalid': errors.add_user_registration }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                                <div v-if="errors.add_user_registration" class="invalid-feedback">
                                                    {{ errors.add_user_registration }}
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Akses Kategori Peminatan</label>
                                                <select v-model="form.category_access" :class="{ 'is-invalid': errors.category_access }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Tampilkan Semua Kategori Peminatan</option>
                                                    <option value="2">Tampilkan Kategori Sesuai Pilihan Peserta</option>
                                                </select>
                                                <div v-if="errors.category_access" class="invalid-feedback">
                                                    {{ errors.category_access }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Tipe Member Saat Awal Registrasi</label>
                                                <select v-model="form.registration_member_type" :class="{ 'is-invalid': errors.registration_member_type }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Member Gratis Tryout</option>
                                                    <option value="2">Member Tryout Berbayar</option>
                                                </select>
                                                <div v-if="errors.registration_member_type" class="invalid-feedback">
                                                    {{ errors.registration_member_type }}
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12" >
                                                <label class="form-label">Akses Login</label>
                                                <multiselect
                                                    v-model="form.login_type"
                                                    :options="loginTypes"
                                                    :multiple="true"
                                                    label="type"
                                                    :close-on-select="true"
                                                    :allow-empty="true"
                                                    track-by="type"
                                                    placeholder="[ Pilih ]"
                                                    :class="{ 'is-invalid': errors.login_type }"
                                                ></multiselect>
                                                <div v-if="errors.login_type" class="invalid-feedback">
                                                    {{ errors.login_type }}
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12" >
                                                <label class="form-label">Batasan Login (Kosongkan jika tidak ada batasan)</label>
                                                <div class="input-group">
                                                    <input v-model="form.device_login_limit" type="number" :class="{ 'is-invalid': errors.device_login_limit }" class="form-control" placeholder="Batasan Login" min="1">
                                                    <span class="input-group-text" id="basic-addon2">Perangkat</span>
                                                    <div v-if="errors.device_login_limit" class="invalid-feedback">
                                                        {{ errors.device_login_limit }}
                                                    </div>
                                                    <small else class="m-1">Jika login dilakukan di 1 perangkat dengan browser yang berbeda, maka akan dianggap sebagai perangkat yang berbeda.</small>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Akses Member Gratis</label>
                                                <multiselect
                                                    v-model="form.free_member_access"
                                                    :options="menuUsers"
                                                    :multiple="true"
                                                    label="description"
                                                    :close-on-select="true"
                                                    :allow-empty="true"
                                                    track-by="code"
                                                    placeholder="[ Pilih ]"
                                                    :class="{ 'is-invalid': errors.free_member_access }"
                                                ></multiselect>
                                                <div v-if="errors.free_member_access" class="invalid-feedback">
                                                    {{ errors.free_member_access }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Akses Member Berbayar</label>
                                                <multiselect
                                                    v-model="form.paid_member_access"
                                                    :options="menuUsers"
                                                    :multiple="true"
                                                    label="description"
                                                    :close-on-select="true"
                                                    :allow-empty="true"
                                                    track-by="code"
                                                    placeholder="[ Pilih ]"
                                                    :class="{ 'is-invalid': errors.paid_member_access }"
                                                ></multiselect>
                                                <div v-if="errors.paid_member_access" class="invalid-feedback">
                                                    {{ errors.paid_member_access }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Aktivasi Kolom Login & Registrasi</h6>
                            </div>
                            <div class="card-body row g-3">
                                <table class="table table-bordered">
                                    <thead class="table-success">
                                        <tr>
                                            <th>No</th>
                                            <th width="25%">Kode</th>
                                            <th width="25%">Translate</th>
                                            <th width="25%">Status</th>
                                            <th width="25%">Form Wajib Diisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(authentication_field, index) in setting.authentication_field" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td><code class="font-14">{{ authentication_field.name }}</code></td>
                                            <td>
                                                <input v-model="form.authentication_field[index].translate" type="text" class="form-control" placeholder="Translaste">
                                            </td>
                                            <td>
                                                <select v-model="form.authentication_field[index].is_active" class="form-control">
                                                    <option value="1">Aktif</option>
                                                    <option value="0">Nonaktif</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select v-model="form.authentication_field[index].is_required" class="form-control" :disabled="form.authentication_field[index].is_active == 0">
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-body row g-3">
                                <div class="col-lg-6 col-md-12">
                                    <label class="form-label">Background Login</label>
                                    <input type="text" class="form-control" v-model="form.login_page_background" :class="{ 'is-invalid': errors.login_page_background }" placeholder="Background Login">
                                    <div v-if="errors.login_page_background" class="invalid-feedback">
                                        {{ errors.login_page_background }}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label class="form-label">Background Registrasi</label>
                                    <input type="text" class="form-control" v-model="form.register_page_background" :class="{ 'is-invalid': errors.register_page_background }" placeholder="Background Registrasi">
                                    <div v-if="errors.register_page_background" class="invalid-feedback">
                                        {{ errors.register_page_background }}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label class="form-label">Background Aktivasi Akun</label>
                                    <input type="text" class="form-control" v-model="form.activation_page_background" :class="{ 'is-invalid': errors.activation_page_background }" placeholder="Background Aktivasi Akun">
                                    <div v-if="errors.activation_page_background" class="invalid-feedback">
                                        {{ errors.activation_page_background }}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <label class="form-label">Background Reset Password</label>
                                    <input type="text" class="form-control" v-model="form.forgot_password_page_background" :class="{ 'is-invalid': errors.forgot_password_page_background }" placeholder="Background Reset Password">
                                    <div v-if="errors.forgot_password_page_background" class="invalid-feedback">
                                        {{ errors.forgot_password_page_background }}
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary btn-sm px-5">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->

</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import reactive
    import { reactive, watch } from 'vue';

    // import Swal
    import Swal from 'sweetalert2';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import Multiselect from '@suadelabs/vue3-multiselect'

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
            Multiselect,
        },

        //props
        props: {
            errors: Object,
            setting: Object,
            menuUsers: Object,
            loginTypes: Object,
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                add_activation_user: props.setting.add_activation_user ?? '',
                add_user_registration: props.setting.add_user_registration ?? '',
                category_access: props.setting.category_access ?? '',
                registration_member_type: props.setting.registration_member_type ?? '',
                login_type: props.setting.login_type ?? '',
                device_login_limit: props.setting.device_login_limit ?? '',
                paid_member_access: props.setting.paid_member_access ?? '',
                free_member_access: props.setting.free_member_access ?? '',
                activation_page_background: props.setting.activation_page_background ?? '',
                forgot_password_page_background: props.setting.forgot_password_page_background ?? '',
                login_page_background: props.setting.login_page_background ?? '',
                register_page_background: props.setting.register_page_background ?? '',
                authentication_field: props.setting.authentication_field.map((field) => ({
                    name: field.name,   
                    translate: field.translate,
                    is_active:field.is_active,   
                    is_required:field.is_required,
                }))
            });

            form.authentication_field.forEach((field, index) => {
                watch(() => field.is_active,
                    (newValue) => {
                        if (newValue == 1) {
                            form.authentication_field[index].is_required = '1'; 
                        }

                        if (newValue == 0) {
                            form.authentication_field[index].is_required = '0'; 
                        }
                    }
                );
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/settings/authentications`, {
                    forceFormData: true,
                    // data
                    add_activation_user: form.add_activation_user,
                    add_user_registration: form.add_user_registration,
                    category_access: form.category_access,
                    registration_member_type: form.registration_member_type,
                    login_type: form.login_type,
                    device_login_limit: form.device_login_limit,
                    paid_member_access: form.paid_member_access,
                    free_member_access: form.free_member_access,
                    activation_page_background: form.activation_page_background,
                    forgot_password_page_background: form.forgot_password_page_background,
                    login_page_background: form.login_page_background,
                    register_page_background: form.register_page_background,
                    authentication_field: form.authentication_field,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Setting Autentikasi Berhasil Diupdate!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    },
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

<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>