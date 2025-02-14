<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Request Penarikan Komisi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Penarikan Komisi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Request Penarikan Komisi</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">                    
                    
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <Link href="/user/affiliates/withdrawals" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show my-3 p-0 px-3 py-2">
                        <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                        <ul class="mt-3">
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 py-3">
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border border-2 mb-3 border-primary">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary">Total Komisi</p>
                                            <h4 class="my-1 text-primary">{{ formatPrice(userCommission.user_commission ? userCommission.user_commission.total_commission : 0) }}</h4>
                                        </div>
                                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i class="bx bx-money"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border border-2 mb-3 border-danger">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Total Sudah Ditarik</p>
                                        <h4 class="my-1 text-danger">{{ formatPrice(userCommission.user_commission ? userCommission.user_commission.total_withdrawals : 0) }}</h4>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i class="bx bx-transfer-alt"></i></div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border border-2 mb-3 border-success">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Komisi Saat Ini</p>
                                        <h4 class="my-1 text-success">{{ formatPrice(userCommission.user_commission ? userCommission.user_commission.current_balance : 0) }}</h4>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class="bx bx-credit-card-front"></i></div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                        <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.error"></div>
                    </div>

                    <div v-if="$page.props.session.success" class="alert alert-success border-0">
                        <i class="fa fa-exclamation-triangle"></i> <div v-html="$page.props.session.success"></div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Jumlah Penarikan Komisi</label>
                            <input type="number" class="form-control" v-model="form.total_withdrawal" :class="{ 'is-invalid': errors.total_withdrawal }" placeholder="Total Penarikan" min="1" onwheel="this.blur()" style="appearance: none; -moz-appearance: textfield; overflow: hidden;">
                            <div v-if="errors.total_withdrawal" class="invalid-feedback">
                                {{ errors.total_withdrawal }}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Dikirim ke Bank</label>
                            <div class="input-group">
                                <select class="form-control" v-model="form.user_bank_id" :class="{ 'is-invalid': errors.user_bank_id }">
                                    <option value="">[ Pilih ]</option>
                                    <option v-for="(userBank, index) in userBanks" :key="index" :value="userBank.id">
                                        {{ userBank.bank_name }} {{ userBank.account_number }} AN {{ userBank.account_name }}
                                    </option>
                                </select>
                                <button class="btn btn-primary" type="button"><Link class="text-white" href="/user/affiliates/user-banks">Tambah Bank</Link></button>
                                <div v-if="errors.user_bank_id" class="invalid-feedback">
                                    {{ errors.user_bank_id }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="card overflow-hidden mb-0 shadow-none border border-1 mb-3 border-info bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0">Biaya Admin Penarikan</p>
                                            <h4 class="my-1">{{ formatPrice($page.props.setting.admin_fee) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="card overflow-hidden mb-0 shadow-none border border-1 mb-3 border-info bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0">Total Komisi Diterima</p>
                                            <h4 class="my-1">{{ formatPrice(form.total_withdrawal - $page.props.setting.admin_fee < 0 ? 0 : form.total_withdrawal - $page.props.setting.admin_fee) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm">Ajukan Penarikan Komisi</button>
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
    import LayoutUser from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import reactive
    import { reactive } from 'vue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutUser,

        // register components
        components: {
            Link,
            Head,
        },

        //props
        props: {
            errors: Object,
            userCommission: Object,
            userBanks: Object,
        },

        // initialization composition API
        setup(props) {
            const form = reactive({
                user_bank_id: '',
                total_withdrawal: '',
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post('/user/affiliates/withdrawals', {
                    user_bank_id: form.user_bank_id,
                    total_withdrawal: form.total_withdrawal,
                });
            }

            // return form state and submit method
            return {
                form,
                submit,
            }
        },
        methods: {
            formatPrice(value) {
                let val = Math.floor(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                return 'Rp.' + val
            },
        }
    }
</script>
