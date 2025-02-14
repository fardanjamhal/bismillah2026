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
                <form @submit.prevent="submit">
                    <div class="col-12">
                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-primary" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <Link href="/admin/settings/applications" class="nav-link active">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-title m-1">Aplikasi</div>
                                            </div>
                                        </Link>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <Link href="/admin/settings/authentications" class="nav-link">
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
                                            
                                            <div class="col-12">
                                                <label class="form-label">Nama Aplikasi</label>
                                                <input type="text" class="form-control" v-model="form.app_name" :class="{ 'is-invalid': errors.app_name }" placeholder="Nama Aplikasi">
                                                <div v-if="errors.app_name" class="invalid-feedback">
                                                    {{ errors.app_name }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Link</label>
                                                <input type="text" class="form-control" v-model="form.app_url" :class="{ 'is-invalid': errors.app_url }" placeholder="Link">
                                                <div v-if="errors.app_url" class="invalid-feedback">
                                                    {{ errors.app_url }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Alamat</label>
                                                <textarea class="form-control" v-model="form.address" :class="{ 'is-invalid': errors.address }" placeholder="Alamat" rows="5"></textarea>
                                                <div v-if="errors.address" class="invalid-feedback">
                                                    {{ errors.address }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Logo</label>
                                                <input type="file" class="form-control" @input="form.logo = $event.target.files[0]" :class="{ 'is-invalid': errors.logo }" placeholder="logo">
                                                <div v-if="errors.logo" class="invalid-feedback">
                                                    {{ errors.logo }}
                                                </div>
                                                <br>
                                                <div v-if="setting.logo">
                                                    <img  v-bind:src="'/storage/upload_files/settings/' + setting.logo" style="width:120px;"/>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Favicon</label>
                                                <input type="file" class="form-control" @input="form.favicon = $event.target.files[0]" :class="{ 'is-invalid': errors.favicon }" placeholder="favicon">
                                                <div v-if="errors.favicon" class="invalid-feedback">
                                                    {{ errors.favicon }}
                                                </div>
                                                <br>
                                                <div v-if="setting.favicon">
                                                    <img  v-bind:src="'/storage/upload_files/settings/' + setting.favicon" style="width:120px;"/>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Background Halaman Login dan Register</label>
                                                <div class="input-group">
                                                    <input type="file" class="form-control" @input="form.authentication_background = $event.target.files[0]" :class="{ 'is-invalid': errors.authentication_background }" placeholder="authentication_background">
                                                    <a href="#" @click.prevent="removeBackground()" class="btn btn-danger" v-if="setting.authentication_background">Hapus Background</a>
                                                </div>

                                                <div v-if="errors.authentication_background" class="invalid-feedback">
                                                    {{ errors.authentication_background }}
                                                </div>
                                                <br>
                                                <div v-if="setting.authentication_background">
                                                    <img  v-bind:src="'/storage/upload_files/settings/' + setting.authentication_background" style="width:120px;"/>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Nama Tertanda (Tanda Tangan)</label>
                                                <input type="text" class="form-control" v-model="form.signed_name" :class="{ 'is-invalid': errors.signed_name }" placeholder="Nama Tertanda">
                                                <div v-if="errors.signed_name" class="invalid-feedback">
                                                    {{ errors.signed_name }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Gambar Tanda Tangan</label>
                                                <input type="file" class="form-control" @input="form.signed_image = $event.target.files[0]" :class="{ 'is-invalid': errors.signed_image }" placeholder="signed_image">
                                                <div v-if="errors.signed_image" class="invalid-feedback">
                                                    {{ errors.signed_image }}
                                                </div>
                                                <br>
                                                <div v-if="setting.signed_image">
                                                    <img  v-bind:src="'/storage/upload_files/settings/' + setting.signed_image" style="width:120px;"/>
                                                </div>
                                            </div>
                                            <div class="col-12" >
                                                <label class="form-label">Zona Waktu</label>
                                                <select v-model="form.timezone" :class="{ 'is-invalid': errors.timezone }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="Asia/Jakarta">WIB (Waktu Indonesia Barat)</option>
                                                    <option value="Asia/Makassar">WITA (Waktu Indonesia Tengah)</option>
                                                    <option value="Asia/Jayapura">WIT (Waktu Indonesia Timur)</option>
                                                </select>
                                                <div v-if="errors.timezone" class="invalid-feedback">
                                                    {{ errors.timezone }}
                                                </div>
                                            </div>
                                            <div class="col-12" >
                                                <label class="form-label">Tipe Sistem Blokir Ujian</label>
                                                <select v-model="form.block_system_type" :class="{ 'is-invalid': errors.block_system_type }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="0">Sistem Blokir di Nonaktifkan</option>
                                                    <option value="1">Ujian Diblokir Setelah Mencapai Limit Keluar</option>
                                                    <option value="2">Hanya Sebagai Peringatan Agar Peserta Tidak Keluar Sesi Ujian</option>
                                                </select>
                                                <div v-if="errors.block_system_type" class="invalid-feedback">
                                                    {{ errors.block_system_type }}
                                                </div>
                                            </div>
                                            <div class="col-12" >
                                                <label class="form-label">Akses Aplikasi</label>
                                                <select v-model="form.public_access" :class="{ 'is-invalid': errors.public_access }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Dapat di Akses Secara Publik</option>
                                                    <option value="0">Hanya IP yang terdaftar (IP Whitelist)</option>
                                                </select>
                                                <div v-if="errors.public_access" class="invalid-feedback">
                                                    {{ errors.public_access }}
                                                </div>
                                            </div>
                                            <div class="col-12" >
                                                <label class="form-label">Mode Grup Sosial Media</label>
                                                <select v-model="form.social_group_mode" :class="{ 'is-invalid': errors.social_group_mode }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Aktif</option>
                                                    <option value="0">Nonaktif</option>
                                                </select>
                                                <div v-if="errors.social_group_mode" class="invalid-feedback">
                                                    {{ errors.social_group_mode }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-body row g-3">
                                <div class="col-12">
                                    <label class="form-label">Tampilkan Latihan Soal Dengan Status :</label>
                                    <multiselect
                                        v-model="form.practice_question_statuses"
                                        :options="dataStatus"
                                        :multiple="true"
                                        :close-on-select="true"
                                        placeholder="[ Pilih ]"
                                        :class="{ 'is-invalid': errors.practice_question_statuses }"
                                    ></multiselect>
                                    <div v-if="errors.practice_question_statuses" class="invalid-feedback">
                                        {{ errors.practice_question_statuses }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Tampilkan Tryout Dengan Status :</label>
                                    <multiselect
                                        v-model="form.tryout_statuses"
                                        :options="dataStatus"
                                        :multiple="true"
                                        :close-on-select="true"
                                        placeholder="[ Pilih ]"
                                        :class="{ 'is-invalid': errors.tryout_statuses }"
                                    ></multiselect>
                                    <div v-if="errors.tryout_statuses" class="invalid-feedback">
                                        {{ errors.tryout_statuses }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Tampilkan Modul/Materi Dengan Status :</label>
                                    <multiselect
                                        v-model="form.module_material_statuses"
                                        :options="dataStatus"
                                        :multiple="true"
                                        :close-on-select="true"
                                        placeholder="[ Pilih ]"
                                        :class="{ 'is-invalid': errors.module_material_statuses }"
                                    ></multiselect>
                                    <div v-if="errors.module_material_statuses" class="invalid-feedback">
                                        {{ errors.module_material_statuses }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Tampilkan Video Pembelajaran Dengan Status :</label>
                                    <multiselect
                                        v-model="form.video_module_statuses"
                                        :options="dataStatus"
                                        :multiple="true"
                                        :close-on-select="true"
                                        placeholder="[ Pilih ]"
                                        :class="{ 'is-invalid': errors.video_module_statuses }"
                                    ></multiselect>
                                    <div v-if="errors.video_module_statuses" class="invalid-feedback">
                                        {{ errors.video_module_statuses }}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Tampilkan Course Dengan Status :</label>
                                    <multiselect
                                        v-model="form.course_statuses"
                                        :options="dataStatus"
                                        :multiple="true"
                                        :close-on-select="true"
                                        placeholder="[ Pilih ]"
                                        :class="{ 'is-invalid': errors.course_statuses }"
                                    ></multiselect>
                                    <div v-if="errors.course_statuses" class="invalid-feedback">
                                        {{ errors.course_statuses }}
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary btn-sm px-5">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
    import { reactive } from 'vue';

    // import Swal
    import Swal from 'sweetalert2';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import Multiselect from '@suadelabs/vue3-multiselect'

    import { Inertia } from '@inertiajs/inertia';

    import QrcodeVue from 'qrcode.vue'

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
            Multiselect,
            QrcodeVue,
        },

        //props
        props: {
            errors: Object,
            setting: Object,
            dataStatus: Object,
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                app_name: props.setting.app_name,
                app_url: props.setting.app_url,
                logo: props.setting.logo,
                favicon: props.setting.favicon,
                signed_name: props.setting.signed_name,
                signed_image: props.setting.signed_image,
                authentication_background: props.setting.authentication_background,
                address: props.setting.address,
                timezone: props.setting.timezone ?? '',
                block_system_type: props.setting.block_system_type ?? '',
                public_access: props.setting.public_access ?? '',
                social_group_mode: props.setting.social_group_mode ?? '',

                practice_question_statuses: props.setting.practice_question_statuses ?? [],
                tryout_statuses: props.setting.tryout_statuses ?? [],
                module_material_statuses: props.setting.module_material_statuses ?? [],
                video_module_statuses: props.setting.video_module_statuses ?? [],
                course_statuses: props.setting.course_statuses ?? [],
            });
            
            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/settings/applications`, {
                    forceFormData: true,
                    // data
                    app_name: form.app_name,
                    app_url: form.app_url,
                    signed_name: form.signed_name,
                    signed_image: form.signed_image,
                    logo: form.logo,
                    favicon: form.favicon,
                    authentication_background: form.authentication_background,
                    address: form.address,
                    timezone: form.timezone,
                    block_system_type: form.block_system_type,
                    public_access: form.public_access,
                    social_group_mode: form.social_group_mode,

                    practice_question_statuses: form.practice_question_statuses,
                    tryout_statuses: form.tryout_statuses,
                    module_material_statuses: form.module_material_statuses,
                    video_module_statuses: form.video_module_statuses,
                    course_statuses: form.course_statuses,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Setting Aplikasi Berhasil Diupdate!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    },
                });
            }

            const removeBackground = () => {
                Swal.fire({
                    title: 'Apakah Anda yakin ?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/admin/settings/delete-background`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Background Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            // return form state and submit method
            return {
                form,
                submit,
                removeBackground,
            }
        }
    }
</script>

<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>