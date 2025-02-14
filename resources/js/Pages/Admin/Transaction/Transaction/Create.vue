<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Tambah Transaksi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Transaksi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Transaksi</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

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

            <div class="row">
                <div class="col-lg-12 col-md-12">
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
                                    <Link href="/admin/exams" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                                </div>
                            </div>
                            <form @submit.prevent="submit" class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Jenis Transaksi</label>
                                    <select @change="changeTransactionType" v-model="form.item_type" :class="{ 'is-invalid': errors.item_type }" class="form-select">
                                        <option value="">[ Pilih ]</option>
                                        <option value="0">Pembelian Latihan Soal</option>
                                        <option value="1">Pembelian Tryout</option>
                                        <option value="2">Pembelian Membership</option>
                                        <option value="3">Top Up Saldo</option>
                                    </select>
                                    <div v-if="errors.item_type" class="invalid-feedback">
                                        {{ errors.item_type }}
                                    </div>
                                </div>

                                <div class="col-12" v-if="form.item_type && form.item_type != '3'">
                                    <label class="form-label">Kategori Peminatan</label>
                                    <select @change="lessonCategoryData" v-model="form.category_id" :class="{ 'is-invalid': errors.category_id }" class="form-select">
                                        <option value="">[ Pilih ]</option>
                                        <option v-for="(category, index) in categories" :key="index" :value="category.id">{{ category.name }}</option>
                                    </select>
                                    <div v-if="errors.category_id" class="invalid-feedback">
                                        {{ errors.category_id }}
                                    </div>
                                </div>

                                <div class="col-12" v-if="form.item_type == '2'">
                                    <label class="form-label">Voucher Membership</label>
                                    <select v-model="form.voucher_id" :class="{ 'is-invalid': errors.voucher_id }" class="form-select">
                                        <option value="">[ Pilih ]</option>
                                        <option v-for="voucher in form.vouchers" :value="voucher.id">{{ voucher.title }} [ {{ formatPrice(voucher.price_after_discount) }} ]</option>
                                    </select>
                                    <div v-if="errors.voucher_id" class="invalid-feedback">
                                        {{ errors.voucher_id }}
                                    </div>
                                </div>

                                <div class="col-12" v-if="form.item_type == '0' || form.item_type == '1'">
                                    <label class="form-label">Kategori Mata Pelajaran</label>
                                    <select @change="lessonData" v-model="form.lesson_category_id" :class="{ 'is-invalid': errors.lesson_category_id }" class="form-select">
                                        <option value="">[ Pilih ]</option>
                                        <option v-for="lessonCategory in form.lessonCategories" :value="lessonCategory.id">{{ lessonCategory.name }}</option>
                                    </select>
                                    <div v-if="errors.lesson_category_id" class="invalid-feedback">
                                        {{ errors.lesson_category_id }}
                                    </div>
                                </div>

                                <div class="col-12" v-if="form.item_type == '0'">
                                    <label class="form-label">Mata Pelajaran</label>
                                    <select @change="examData" v-model="form.lesson_id" :class="{ 'is-invalid': errors.lesson_id }" class="form-select">
                                        <option value="">[ Pilih ]</option>
                                        <option v-for="lesson in form.lessons" :value="lesson.id">{{ lesson.name }}</option>
                                    </select>
                                    <div v-if="errors.lesson_id" class="invalid-feedback">
                                        {{ errors.lesson_id }}
                                    </div>
                                </div>

                                <div class="col-12" v-if="form.item_type == '0'">
                                    <label class="form-label">Latihan Soal</label>
                                    <select v-model="form.exam_id" :class="{ 'is-invalid': errors.exam_id }" class="form-select">
                                        <option value="">[ Pilih ]</option>
                                        <option v-for="exam in form.exams" :value="exam.id">{{ exam.title }} [ {{ formatPrice(exam.price_after_discount) }} ]</option>
                                    </select>
                                    <div v-if="errors.exam_id" class="invalid-feedback">
                                        {{ errors.exam_id }}
                                    </div>
                                </div>

                                <div class="col-12" v-if="form.item_type == '1'">
                                    <label class="form-label">Tryout</label>
                                    <select v-model="form.exam_group_id" :class="{ 'is-invalid': errors.exam_group_id }" class="form-select">
                                        <option value="">[ Pilih ]</option>
                                        <option v-for="examGroup in form.examGroups" :value="examGroup.id">{{ examGroup.title }} [ {{ formatPrice(examGroup.price_after_discount) }} ]</option>
                                    </select>
                                    <div v-if="errors.exam_group_id" class="invalid-feedback">
                                        {{ errors.exam_group_id }}
                                    </div>
                                </div>

                                <div class="col-12" v-if="form.item_type == '3'">
                                    <label class="form-label">Nominal Saldo</label>
                                    <input type="number" min="1" class="form-control" v-model="form.total_payment" :class="{ 'is-invalid': errors.total_payment }" placeholder="Nominal Saldo">
                                    <div v-if="errors.total_payment" class="invalid-feedback">
                                        {{ errors.total_payment }}
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Peserta</label>
                                    <multiselect
                                        :class="{ 'is-invalid': errors.user_id }"
                                        v-model="form.user_id"
                                       :options="users"
                                        :multiple="false"
                                        label="name"
                                        :close-on-select="true"
                                        :allow-empty="false"
                                        track-by="id"
                                        placeholder="[ Pilih ]"
                                    ></multiselect>
                                    <div v-if="errors.user_id" class="invalid-feedback">
                                        {{ errors.user_id }}
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Metode Pembayaran</label>
                                    <select v-model="form.payment_method" :class="{ 'is-invalid': errors.payment_method }" class="form-select">
                                        <option value="">[ Pilih ]</option>
                                        <option v-for="paymentMethod in filteredPaymentMethods" :key="paymentMethod.code" :value="paymentMethod.code">
                                            {{ paymentMethod.name }}
                                        </option>
                                    </select>
                                    <div v-if="errors.payment_method" class="invalid-feedback">
                                        {{ errors.payment_method }}
                                    </div>
                                </div>

                                <div class="col-12" v-if="form.payment_method == 'account_balance'">
                                    <label class="form-label">Potong Saldo Akun</label>
                                    <select v-model="form.reduce_account_balance" :class="{ 'is-invalid': errors.reduce_account_balance }" class="form-select">
                                        <option value="">[ Pilih ]</option>
                                        <option value="1">Ya</option>
                                        <option value="0">Tidak</option>
                                    </select>
                                    <div v-if="errors.reduce_account_balance" class="invalid-feedback">
                                        {{ errors.reduce_account_balance }}
                                    </div>
                                </div>

                                <div class="col-12" v-if="form.item_type == '2'">
                                    <label class="form-label">Tanggal Kedaluarsa Membership (Kosongkan untuk otomatis sesuai durasi voucher)</label>
                                    <input type="date" class="form-control" v-model="form.expired_date" :class="{ 'is-invalid': errors.expired_date }" placeholder="Tanggal Kedaluarsa Membership">
                                    <div v-if="errors.expired_date" class="invalid-feedback">
                                        {{ errors.expired_date }}
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Tanggal Transaksi (Kosongkan untuk otomatis sesuai tanggal dibuatnya transaksi)</label>
                                    <input type="datetime-local" class="form-control" v-model="form.created_at" :class="{ 'is-invalid': errors.created_at }" placeholder="Tanggal Transaksi">
                                    <div v-if="errors.created_at" class="invalid-feedback">
                                        {{ errors.created_at }}
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Status Transaksi</label>
                                    <select v-model="form.transaction_status" :class="{ 'is-invalid': errors.transaction_status }" class="form-select">
                                        <option value="">[ Pilih ]</option>
                                        <option v-if="form.payment_method != 'account_balance'" value="pending">Pending - Belum Melakukan Pembayaran</option>
                                        <option v-if="form.payment_method != 'account_balance'" value="paid">Paid - Sudah bayar dan Perlu Approval dari Admin</option>
                                        <option v-if="form.payment_method != 'account_balance'" value="failed">Failed - Transaksi Gagal</option>
                                        <option value="done">Done - Transaksi Selesai</option>
                                        <option v-if="form.payment_method != 'account_balance'" value="expired">Expired - Transaksi Kadaluarsa</option>
                                    </select>
                                    <div v-if="errors.transaction_status" class="invalid-feedback">
                                        {{ errors.transaction_status }}
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-sm px-5">Buat Transaksi</button>
                                </div>
                            </form>
                        </div>
                    </div>
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

    import debounce from 'lodash/debounce';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    //import ref 
    import { ref } from 'vue';

    import Multiselect from '@suadelabs/vue3-multiselect'

    import { Inertia } from '@inertiajs/inertia';

    //import axios
    import axios from 'axios';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
            Multiselect
        },
        //props
        props: {
            errors: Object,
            categories: Object,
            memberCategories: Object,
            users: Object,
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
                    if (this.form.item_type == 3 && paymentMethod.code === 'account_balance') {
                        return false;
                    }
                    return true;
                });
            }
        },

        // initialization composition API
        setup() {
            const form = reactive({
                item_type: '',
                category_id: '',
                voucher_id: '',
                lesson_category_id: '',
                lesson_id: '',
                exam_id: '',
                exam_group_id: '',
                user_id: '',
                expired_date: '',
                total_payment: '',
                payment_method: '',
                reduce_account_balance: '',
                transaction_status: '',
                created_at: '',

                // get form API
                lessonCategories: [],
                lessons: [],
                exams: [],
                examGroups: [],
                users: [],
                vouchers: [],
            });

            const changeTransactionType = () => {
                form.category_id = '';
                form.voucher_id = '';
                form.lesson_category_id = '';
                form.lesson_id = '';
                form.exam_id = '';
                form.exam_group_id = '';
                form.user_id = '';
                form.expired_date = '';
                form.total_payment = '';
                form.payment_method = '';
                form.created_at = '';
                form.reduce_account_balance = '',
                form.transaction_status = '';
            }

            const lessonCategoryData = () => {
                form.lesson_category_id = '';
                form.lesson_id = '';
                form.exam_id = '';
                form.exam_group_id = '';

                axios
                    .get(`/admin/categories/${form.category_id}/vouchers`)
                    .then(response => {
                        form.vouchers = response.data;
                    })
                    .catch(error => console.error(error));

                axios
                    .get(`/admin/categories/${form.category_id}/lesson-categories`)
                    .then(response => {
                        form.lessonCategories = response.data;
                    })
                    .catch(error => console.error(error));
            };

            const lessonData = () => {
                form.lesson_id = '';
                form.exam_id = '';

                axios
                    .get(`/admin/lesson-categories/${form.lesson_category_id}/exam-groups`)
                    .then(response => {
                        form.examGroups = response.data;
                    })
                    .catch(error => console.error(error));

                axios
                    .get(`/admin/lesson-categories/${form.lesson_category_id}/lessons`)
                    .then(response => {
                        form.lessons = response.data;
                    })
                    .catch(error => console.error(error));
            };

            const examData = () => {
                form.exam_id = '';

                axios
                    .get(`/admin/lessons/${form.lesson_id}/exams`)
                    .then(response => {
                        form.exams = response.data;
                    })
                    .catch(error => console.error(error));
            };


            // submit method
            const submit = () => {
                // send data to server
                Inertia.post('/admin/transactions', {
                    // data
                    item_type: form.item_type,
                    category_id: form.category_id,
                    voucher_id: form.voucher_id,
                    lesson_category_id: form.lesson_category_id,
                    lesson_id: form.lesson_id,
                    exam_id: form.exam_id,
                    exam_group_id: form.exam_group_id,
                    user_id: form.user_id,
                    expired_date: form.expired_date,
                    total_payment: form.total_payment,
                    created_at: form.created_at,
                    payment_method: form.payment_method,
                    reduce_account_balance: form.reduce_account_balance,
                    transaction_status: form.transaction_status,
                });
            }

            // return form state and submit method
            return {
                form,
                submit,
                lessonCategoryData,
                lessonData,
                examData,

                changeTransactionType,
            }
        }
    }
</script>

<style src="@suadelabs/vue3-multiselect/dist/vue3-multiselect.css"></style>
