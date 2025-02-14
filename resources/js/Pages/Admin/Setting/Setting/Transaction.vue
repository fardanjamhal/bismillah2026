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
                                    <Link href="/admin/settings/authentications" class="nav-link">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-title m-1">Autentikasi</div>
                                        </div>
                                    </Link>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <Link href="/admin/settings/transactions" class="nav-link active">
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
                                    <form @submit.prevent="submit">
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
                                                <label class="form-label">Aktifkan Penjualan Latihan Soal</label>
                                                <select v-model="form.enable_practice_question_sales" :class="{ 'is-invalid': errors.enable_practice_question_sales }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                                <div v-if="errors.enable_practice_question_sales" class="invalid-feedback">
                                                    {{ errors.enable_practice_question_sales }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Tipe Penjualan Latihan Soal</label>
                                                <select v-model="form.practice_question_sales_type" :class="{ 'is-invalid': errors.practice_question_sales_type }" class="form-select" :disabled="form.enable_practice_question_sales == 0">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="0" v-if="form.enable_practice_question_sales == 0">Penjualan Latihan Soal dinonaktifkan</option>
                                                    <option value="1">Penjualan Per Latihan Soal</option>
                                                    <option value="2">Penjualan Dengan Membership Bulanan</option>
                                                    <option value="3">Penjualan Per Latihan Soal & Membership Bulanan</option>
                                                </select>
                                                <div v-if="errors.practice_question_sales_type" class="invalid-feedback">
                                                    {{ errors.practice_question_sales_type }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Aktifkan Penjualan Tryout</label>
                                                <select v-model="form.enable_tryout_sales" :class="{ 'is-invalid': errors.enable_tryout_sales }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                                <div v-if="errors.enable_tryout_sales" class="invalid-feedback">
                                                    {{ errors.enable_tryout_sales }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Tipe Penjualan Tryout</label>
                                                <select v-model="form.tryout_sales_type" :class="{ 'is-invalid': errors.tryout_sales_type }" class="form-select" :disabled="form.enable_tryout_sales == 0">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="0" v-if="form.enable_tryout_sales == 0">Penjualan Tryout dinonaktifkan</option>
                                                    <option value="1">Penjualan Per Tryout</option>
                                                    <option value="2">Penjualan Dengan Membership Bulanan</option>
                                                    <option value="3">Penjualan Per Tryout & Membership Bulanan</option>
                                                </select>
                                                <div v-if="errors.tryout_sales_type" class="invalid-feedback">
                                                    {{ errors.tryout_sales_type }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Aktifkan Penjualan Modul / Materi</label>
                                                <select v-model="form.enable_module_material_sales" :class="{ 'is-invalid': errors.enable_module_material_sales }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                                <div v-if="errors.enable_module_material_sales" class="invalid-feedback">
                                                    {{ errors.enable_module_material_sales }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Tipe Penjualan Modul / Materi</label>
                                                <select v-model="form.module_material_sales_type" :class="{ 'is-invalid': errors.module_material_sales_type }" class="form-select" :disabled="form.enable_module_material_sales == 0">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="0" v-if="form.enable_module_material_sales == 0">Penjualan Modul dinonaktifkan</option>
                                                    <option value="1">Penjualan Per Modul</option>
                                                    <option value="2">Penjualan Dengan Membership Bulanan</option>
                                                    <option value="3">Penjualan Per Modul & Membership Bulanan</option>
                                                </select>
                                                <div v-if="errors.module_material_sales_type" class="invalid-feedback">
                                                    {{ errors.module_material_sales_type }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Aktifkan Penjualan Video Pembelajaran</label>
                                                <select v-model="form.enable_video_module_sales" :class="{ 'is-invalid': errors.enable_video_module_sales }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                                <div v-if="errors.enable_video_module_sales" class="invalid-feedback">
                                                    {{ errors.enable_video_module_sales }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Tipe Penjualan Video Pembelajaran</label>
                                                <select v-model="form.video_module_sales_type" :class="{ 'is-invalid': errors.video_module_sales_type }" class="form-select" :disabled="form.enable_video_module_sales == 0">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="0" v-if="form.enable_video_module_sales == 0">Penjualan Video Pembelajaran dinonaktifkan</option>
                                                    <option value="1">Penjualan Per Video Pembelajaran</option>
                                                    <option value="2">Penjualan Dengan Membership Bulanan</option>
                                                    <option value="3">Penjualan Per Video Pembelajaran & Membership Bulanan</option>
                                                </select>
                                                <div v-if="errors.video_module_sales_type" class="invalid-feedback">
                                                    {{ errors.video_module_sales_type }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Aktifkan Penjualan Course</label>
                                                <select v-model="form.enable_course_sales" :class="{ 'is-invalid': errors.enable_course_sales }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                                <div v-if="errors.enable_course_sales" class="invalid-feedback">
                                                    {{ errors.enable_course_sales }}
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-12">
                                                <label class="form-label">Tipe Penjualan Course</label>
                                                <select v-model="form.course_sales_type" :class="{ 'is-invalid': errors.course_sales_type }" class="form-select" :disabled="form.enable_course_sales == 0">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="0" v-if="form.enable_course_sales == 0">Penjualan Course dinonaktifkan</option>
                                                    <option value="1">Penjualan Per Course</option>
                                                    <option value="2">Penjualan Dengan Membership Bulanan</option>
                                                    <option value="3">Penjualan Per Course & Membership Bulanan</option>
                                                </select>
                                                <div v-if="errors.course_sales_type" class="invalid-feedback">
                                                    {{ errors.course_sales_type }}
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Metode Pembayaran Yang Diaktifkan</label>
                                                <multiselect
                                                    v-model="form.payment_methods"
                                                    :options="paymentMethods"
                                                    :multiple="true"
                                                    label="name"
                                                    :close-on-select="true"
                                                    :allow-empty="true"
                                                    track-by="code"
                                                    placeholder="[ Pilih ]"
                                                    :class="{ 'is-invalid': errors.payment_methods }"
                                                ></multiselect>
                                                <div v-if="errors.payment_methods" class="invalid-feedback">
                                                    {{ errors.payment_methods }}
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Mode Pembayaran Transfer Otomatis (Payment Gateway)</label>
                                                <select  v-model="form.payment_gateway_mode"  :class="{ 'is-invalid': errors.payment_gateway_mode }"  class="form-select"  :disabled="form.payment_methods && !form.payment_methods.some(item => item.code === 'automatic_transfer_midtrans')">
                                                    <option value="">[ Pilih ]</option>
                                                    <option v-if="form.payment_methods && !form.payment_methods.some(item => item.code === 'automatic_transfer_midtrans')" value="0">Transfer Otomatis dinonaktifkan</option>
                                                    <option value="1">Development</option>
                                                    <option value="2">Production</option>
                                                </select>
                                                <div v-if="errors.payment_gateway_mode" class="invalid-feedback">
                                                    {{ errors.payment_gateway_mode }}
                                                </div>
                                            </div>

                                            <div class="col-12" v-if="form.payment_methods && form.payment_methods.some(item => item.code === 'account_balance')">
                                                <label class="form-label">Minimal Topup Saldo</label>
                                                <input type="number" class="form-control" v-model="form.minimum_topup_balance" :class="{ 'is-invalid': errors.minimum_topup_balance }" placeholder="Minimal Topup Saldo">
                                                <div v-if="errors.minimum_topup_balance" class="invalid-feedback">
                                                    {{ errors.minimum_topup_balance }}
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Aktifkan Notifikasi Whatsapp Untuk Transaksi</label>
                                                <select v-model="form.add_transaction_notification" :class="{ 'is-invalid': errors.add_transaction_notification }" class="form-select">
                                                    <option value="">[ Pilih ]</option>
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                                <div v-if="errors.add_transaction_notification" class="invalid-feedback">
                                                    {{ errors.add_transaction_notification }}
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary btn-sm px-5">Submit</button>
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
    </div>
    <!--end page wrapper -->

     <!-- Modal -->
    <div class="modal fade" id="whatsappModal" tabindex="-1" aria-labelledby="whatsappModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="whatsappModalLabel">Tes Kirim Whatsapp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div v-if="whatsappErrors.length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                        <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                        <ul class="mt-3">
                            <li v-for="error in whatsappErrors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <label for="whatsappNumber" class="form-label">Nomor Whatsapp</label>
                        <input type="text" class="form-control" v-model="whatsappForm.phone_number" id="whatsappNumber" placeholder="Masukkan Nomor. Contoh : 6281212126043">
                    </div>
                    <div class="mb-3">
                        <label for="whatsappMessage" class="form-label">Pesan</label>
                        <textarea class="form-control" v-model="whatsappForm.message" id="whatsappMessage" rows="3" placeholder="Masukkan Pesan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="sendWhatsapp">Kirim Pesan</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import reactive dan watch
    import { reactive, watch } from 'vue';

    // import Swal
    import Swal from 'sweetalert2';

    // import Head from Inertia
    import { Head } from '@inertiajs/inertia-vue3';

    import Multiselect from '@suadelabs/vue3-multiselect';

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
            paymentMethods: Object,
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                enable_practice_question_sales: props.setting.enable_practice_question_sales ?? '',
                practice_question_sales_type: props.setting.practice_question_sales_type ?? '',

                enable_tryout_sales: props.setting.enable_tryout_sales ?? '',
                tryout_sales_type: props.setting.tryout_sales_type ?? '',

                enable_module_material_sales: props.setting.enable_module_material_sales ?? '',
                module_material_sales_type: props.setting.module_material_sales_type ?? '',

                enable_video_module_sales: props.setting.enable_video_module_sales ?? '',
                video_module_sales_type: props.setting.video_module_sales_type ?? '',

                enable_course_sales: props.setting.enable_course_sales ?? '',
                course_sales_type: props.setting.course_sales_type ?? '',

                add_transaction_notification: props.setting.add_transaction_notification ?? '',
                payment_methods: props.setting.payment_methods ?? '',
                payment_gateway_mode: props.setting.payment_gateway_mode ?? '',
                minimum_topup_balance: props.setting.minimum_topup_balance ?? '',
            });

            const whatsappForm = reactive({
                phone_number: '',
                message: '',
            });

            const whatsappErrors = reactive([]);

            watch(() => form.enable_practice_question_sales, (newVal) => {
                form.practice_question_sales_type = newVal == 0 ? 0 : '';
            });

            watch(() => form.enable_tryout_sales, (newVal) => {
                form.tryout_sales_type = newVal == 0 ? 0 : '';
            });

            watch(() => form.enable_module_material_sales, (newVal) => {
                form.module_material_sales_type = newVal == 0 ? 0 : '';
            });

            watch(() => form.enable_video_module_sales, (newVal) => {
                form.video_module_sales_type = newVal == 0 ? 0 : '';
            });

            watch(() => form.enable_course_sales, (newVal) => {
                form.course_sales_type = newVal == 0 ? 0 : '';
            });

            watch(() => form.payment_methods, (newVal) => {
                form.payment_gateway_mode = newVal.some(item => item.code === 'automatic_transfer_midtrans') ? '' : 0;
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/settings/transactions`, {
                    // data
                    enable_practice_question_sales: form.enable_practice_question_sales,
                    practice_question_sales_type: form.practice_question_sales_type,

                    enable_tryout_sales: form.enable_tryout_sales,
                    tryout_sales_type: form.tryout_sales_type,

                    enable_module_material_sales: form.enable_module_material_sales,
                    module_material_sales_type: form.module_material_sales_type,

                    enable_video_module_sales: form.enable_video_module_sales,
                    video_module_sales_type: form.video_module_sales_type,

                    enable_course_sales: form.enable_course_sales,
                    course_sales_type: form.course_sales_type,

                    add_transaction_notification: form.add_transaction_notification,
                    payment_methods: form.payment_methods,
                    payment_gateway_mode: form.payment_gateway_mode,
                    minimum_topup_balance: form.minimum_topup_balance,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Setting Transaksi Berhasil Diupdate!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    },
                });
            };

            const showModalWhatsapp = () => {
                const modal = new bootstrap.Modal(document.getElementById('whatsappModal'));
                modal.show();
            };

            const sendWhatsapp = () => {
                whatsappErrors.length = 0; // Reset errors

                if (!whatsappForm.phone_number.trim()) {
                    whatsappErrors.push('Nomor WhatsApp harus diisi.');
                }

                if (!whatsappForm.message.trim()) {
                    whatsappErrors.push('Pesan harus diisi.');
                }

                if (whatsappErrors.length > 0) {
                    return; // Do not proceed if there are validation errors
                }

                Inertia.post(`/admin/settings/test-send-whatsapp`, {
                    phone_number: whatsappForm.phone_number,
                    message: whatsappForm.message,
                }, {
                    onSuccess: () => {
                        Swal.fire({
                            title: 'Sukses!',
                            text: 'Pesan telah berhasil dikirim. Silakan cek pesan di nomor tujuan.',
                            icon: 'success',
                            confirmButtonText: 'Ok',
                        });

                        whatsappForm.phone_number = '';
                        whatsappForm.message = '';
                        whatsappErrors.length = 0; // Reset errors after successful submission
                    },
                });

                const modalWhatsapp = bootstrap.Modal.getInstance(document.getElementById('whatsappModal'));
                modalWhatsapp.hide();
            };

            const removeBackground = () => {
                Swal.fire({
                    title: 'Apakah Anda yakin ?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
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
                });
            };

            // return form state and submit method
            return {
                form,
                submit,
                removeBackground,
                showModalWhatsapp,
                whatsappForm,
                sendWhatsapp,
                whatsappErrors,
            };
        },
    };
</script>

<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>