<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Edit Bank</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Bank</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Bank</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                        <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                        <ul class="mt-3">
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <Link href="/admin/voucher-codes" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Kode Voucher</label>
                            <input type="text" class="form-control" v-model="form.code" :class="{ 'is-invalid': errors.code }" placeholder="Kode Voucher" readonly>
                            <div v-if="errors.code" class="invalid-feedback">
                                {{ errors.code }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nominal Voucher</label>
                            <input type="number" min="0" class="form-control" v-model="form.nominal_voucher" :class="{ 'is-invalid': errors.nominal_voucher }" placeholder="Nominal Voucher">
                            <div v-if="errors.nominal_voucher" class="invalid-feedback">
                                {{ errors.nominal_voucher }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Batasan User (Kosong Jika Tidak Ada)</label>
                            <input type="number" min="0" class="form-control" v-model="form.user_limit" :class="{ 'is-invalid': errors.user_limit }" placeholder="Batasan User">
                            <div v-if="errors.user_limit" class="invalid-feedback">
                                {{ errors.user_limit }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tanggal Kedaluarsa (Kosongkan Jika Tidak Ada)</label>
                            <input type="date" class="form-control" v-model="form.expired_date" :class="{ 'is-invalid': errors.expired_date }" placeholder="Tanggal Kedaluarsa">
                            <div v-if="errors.expired_date" class="invalid-feedback">
                                {{ errors.expired_date }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Status</label>
                            <select v-model="form.is_active" :class="{ 'is-invalid': errors.is_active }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Aktif</option>
                                <option value="0">Non Aktif</option>
                            </select>
                            <div v-if="errors.is_active" class="invalid-feedback">
                                {{ errors.is_active }}
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm px-5">Submit</button>
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
    import { reactive } from 'vue';

    // import Swal
    import Swal from 'sweetalert2';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
        },

        //props
        props: {
            errors: Object,
            voucherCode: Object
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                code: props.voucherCode.code,
                nominal_voucher: props.voucherCode.nominal_voucher,
                user_limit: props.voucherCode.user_limit,
                expired_date: props.voucherCode.expired_date,
                is_active: props.voucherCode.is_active,
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post(`/admin/voucher-codes/${props.voucherCode.id}`, {
                    _method: 'put',
                    // data
                    id: props.voucherCode.id,
                    code: form.code,
                    nominal_voucher: form.nominal_voucher,
                    user_limit: form.user_limit,
                    expired_date: form.expired_date,
                    is_active: form.is_active,
                }, {
                    onSuccess: () => {
                        //show success alert
                        Swal.fire({
                            title: 'Success!',
                            text: 'Kode Voucher Berhasil Diupdate!.',
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
