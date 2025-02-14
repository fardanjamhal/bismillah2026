<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Pembelian {{ typeTranslation }}</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">{{ typeTranslation }}</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Pembelian {{ typeTranslation }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-lg-12">
                    <div v-if="$page.props.session.error" class="alert alert-danger border-0 alert-dismissible fade show">
                        <div v-html="$page.props.session.error"></div>
                    </div>
                    <div v-if="$page.props.session.success" class="alert alert-success border-0 alert-dismissible fade show">
                        <div v-html="$page.props.session.success"></div>
                    </div>
                    <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                        <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                        <ul class="mt-3">
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-12">
                    <form @submit.prevent="submit">
                        <div class="card border-top border-0 border-3 border-primary">
                            <div class="card-header">
                                <div class="d-lg-flex align-items-center">
                                    <h6>Detail Pembelian {{ typeTranslation }}</h6>
                                    <div class="ms-auto">
                                        <Link :href="urlBack" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="row mb-3" v-if="type != 'topupBalance'">
                                    <label class="col-sm-3 col-form-label">Kategori</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" :value="purchase.category.name" disabled>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label"> {{ type == 'topupBalance' ? 'Deskripsi Pembelian' : 'Pembelian ' + typeTranslation }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" :value="purchase.title" disabled style="background-color: #fff;">
                                    </div>
                                </div>
                                <div class="row mb-3" v-if="type == 'voucher'">
                                    <label class="col-sm-3 col-form-label">Masa Aktif</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" :value="purchase.active_period + (purchase.period_type == 'day' ? ' Hari' : ' Bulan' )" disabled style="background-color: #fff;">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">{{type == 'topupBalance' ? 'Nominal' : 'Harga' }} {{ typeTranslation }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" :value="formatPrice(purchase.price_after_discount)" disabled style="background-color: #fff;">
                                    </div>
                                </div>
                                <div class="row mb-3"  v-if="type != 'topupBalance'">
                                    <label class="col-sm-3 col-form-label">Kategori Member</label>
                                    <div class="col-sm-9">
                                        <div v-if="purchase.member_categories.length">
                                            <div v-for="(member_category, index) in purchase.member_categories" :key="index" style="display: inline;">
                                                <span class="badge bg-success ms-1">{{ member_category.name }}</span>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <span class="badge bg-success ms-1">Akses Untuk Seluruh Tipe Member</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Metode Pembayaran</label>
                                    <div class="col-sm-9">
                                        <select v-model="form.payment_method" :class="{ 'is-invalid': errors.payment_method }" class="form-select">
                                            <option value="">[ Pilih Metode Pembayaran ]</option>
                                            <!-- Gunakan filteredPaymentMethods dari computed property -->
                                            <option v-for="paymentMethod in filteredPaymentMethods" :key="paymentMethod.code" :value="paymentMethod.code">
                                                {{ paymentMethod.name }}
                                            </option>
                                        </select>
                                        <div v-if="errors.payment_method" class="invalid-feedback">
                                            {{ errors.payment_method }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3" v-if="paymentMethods.find(method => method.code === form.payment_method) && paymentMethods.find(method => method.code === form.payment_method).show_description == 1">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <div class="alert border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2 alert-success">
                                            {{ paymentMethods.find(method => method.code === form.payment_method).description }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-sm-3 col-form-label"></label>
                                    <div class="col-sm-9">
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button class="btn btn-primary btn-sm px-4" onclick="buy()">Lanjutkan ke pembayaran</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!--end row-->
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

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    // import Swal
    import Swal from 'sweetalert2';

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
            purchase: Object,
            type: Object,
            typeTranslation: Object,
            urlBack: Object,
            paymentMethods: Object,
        },
        setup(props) {
            const form = reactive({
                payment_method: '',
                price: '',
            });

            // submit method
            const submit = () => {
                Swal.fire({
                    title: 'Selamat',
                    text: "Selangkah lagi pembelianmu berhasil",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Lanjutkan',
                    cancelButtonText: 'Batalkan'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        // send data to server
                        Inertia.post(`/user/transactions/${props.type}/${props.purchase.id}/buy`, {
                            // data
                            payment_method: form.payment_method,
                            price: props.purchase.price_after_discount,
                        });
                    }
                })
            }

            // return form state and submit method
            return {
                form,
                submit,
            }
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
        },
        computed: {
            filteredPaymentMethods() {
                return this.$page.props.setting.payment_methods.filter(paymentMethod => {
                    if (this.type === 'topupBalance' && paymentMethod.code === 'account_balance') {
                        return false;
                    }
                    return true;
                });
            }
        }
    }
</script>
