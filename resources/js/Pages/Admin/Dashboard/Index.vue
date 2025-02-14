<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Kategori</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <div 
                class="row row-cols-1 row-cols-md-2"
                :class="{'row-cols-xl-4': canDisplayAdminTransactions, 'row-cols-xl-3': !canDisplayAdminTransactions}"
                v-if="$page.props.auth.user.level == 1"
                >
                <div class="col">
                    <div class="card radius-10 bg-primary bg-gradient">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-white">Peserta</p>
                                    <h4 class="my-1 text-white">{{ totalStudent }}</h4>
                                </div>
                                <div class="text-white ms-auto font-35"><i class='bx bxs-group'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-danger bg-gradient">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-white">Akun Aktif</p>
                                    <h4 class="my-1 text-white">{{ totalStudentActive }}</h4>
                                </div>
                                <div class="text-white ms-auto font-35"><i class='bx bxs-group'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 bg-warning bg-gradient">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-dark">Akun Non-Aktif</p>
                                    <h4 class="text-dark my-1">{{ totalStudentNonActive }}</h4>
                                </div>
                                <div class="text-dark ms-auto font-35"><i class='bx bx-user-pin'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col" v-if="canDisplayAdminTransactions">
                    <div class="card radius-10 bg-success bg-gradient">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-white">Peserta Menjadi Member</p>
                                    <h4 class="my-1 text-white">{{ totalStudentMember }}</h4>
                                </div>
                                <div class="text-white ms-auto font-35"><i class='bx bx-comment-detail'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4" v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Transakasi Hari Ini</p>
                                    <h4 class="my-1 text-info">{{ totalTransactionToday }}</h4>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-cart'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Transaksi Bulan Ini</p>
                                    <h4 class="my-1 text-success">{{ totalTransactionMonthly }}</h4>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-bar-chart-alt-2' ></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Transaksi Tahun Ini</p>
                                    <h4 class="my-1 text-danger">{{ totalTransactionYearly }}</h4>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i class='bx bxs-wallet'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Pemasukan Bulan Ini</p>
                                    <h4 class="my-1 text-warning">0</h4>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto"><i class='bx bxs-group'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4" v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Transaksi Pending</p>
                                    <h4 class="my-1">{{ totalTransactionPending }}</h4>
                                </div>
                                <div class="widgets-icons bg-light-warning text-warning ms-auto"><i class="bx bxs-wallet"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Transaksi Dibayar</p>
                                    <h4 class="my-1">{{ totalTransactionPaid }}</h4>
                                </div>
                                <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bxs-wallet"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Transaksi Gagal</p>
                                    <h4 class="my-1">{{ totalTransactionFailed }}</h4>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class="bx bxs-wallet"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Transaksi Selesai</p>
                                    <h4 class="my-1">{{ totalTransactionDone }}</h4>
                                </div>
                                <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class="bx bxs-wallet"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3" v-if="$page.props.auth.user.level == 3">
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Paket Soal Dibuat</p>
                                    <h4 class="my-1 text-info">{{ totalQuestionTitleByUser }}</h4>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-cart'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Latihan Soal Dibuat</p>
                                    <h4 class="my-1 text-success">{{ totalExamByUser }}</h4>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-bar-chart-alt-2' ></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card radius-10 border-start border-0 border-3 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Tryout Dibuat</p>
                                    <h4 class="my-1 text-danger">{{ totalQuestionTitleByUser }}</h4>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i class='bx bxs-wallet'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

            <div class="card radius-10 border-start border-0 border-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-3">Pengumuman</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Perihal</th>
                                    <th>Dibuat</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!announcementSummaries.length">
                                    <td colspan="7" align="center">Data Pengumuman kosong</td>
                                </tr>
                                <tr v-for="(announcementSummary, index) in announcementSummaries" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td>{{ announcementSummary.title }}</td>
                                    <td>{{ announcementSummary.created_at }}</td>
                                    <td width="25px">
                                        <div class="d-flex order-actions">
                                            <Link :href="`/admin/announcements/${announcementSummary.id}`" class="ms-2"><i class='bx bx-grid-alt'></i></Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div>
                        <apexchart :width="chart.width" :height="chart.height" :type="chart.type" :options="chart.options" :series="chart.series"></apexchart>
                    </div>
                </div>
            </div> -->

            <div class="card radius-10 border-start border-0 border-3 border-primary" v-if="canDisplayAdminTransactions && $page.props.auth.user.level == 1">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-3">Transaksi Bulan Ini</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>User</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Total Pembayaran</th>
                                    <!-- <th>Metode Pembayaran</th> -->
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(transaction, index) in transactions.data" :key="index">
                                    <td>{{ ++index + (transactions.current_page - 1) * transactions.per_page }}</td>
                                    <td>{{ transaction.code }}</td>
                                    <td>{{ transaction.user.name }}</td>
                                    <td>{{ transaction.description }}</td>
                                    <td>{{ transaction.created_at }}</td>
                                    <td>{{ formatPrice(transaction.total_payment) }}</td>
                                    <!-- <td>{{ transaction.payment_method == 'account_balance' ? 'Saldo Akun' : ''}}</td> -->
                                    <td>
                                        <span class="badge bg-warning" v-if="transaction.transaction_status == 'pending'">Pending</span>
                                        <span class="badge bg-primary" v-if="transaction.transaction_status == 'paid'">Paid</span>
                                        <span class="badge bg-danger" v-if="transaction.transaction_status == 'expired'">Expired</span>
                                        <span class="badge bg-danger" v-if="transaction.transaction_status == 'failed'">Failed</span>
                                        <span class="badge bg-success" v-if="transaction.transaction_status == 'done'">Done</span>
                                    </td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <Link :href="`/admin/transactions/${transaction.id}`" style="margin:5px;"><i class='bx bx-grid-alt'></i></Link>
                                            <Link :href="`/admin/transactions/${transaction.id}/invoice`" style="margin:5px;"><i class='bx bx-credit-card-front'></i></Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="9" v-if="!transactions.data.length">Data Tidak Tersedia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="transactions.links" align="end" />

                </div>
            </div>

            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <h6 class="mb-3">Informasi Kategori Peminatan</h6>
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4" v-for="(totalDataInCategory, index) in totalDataInCategories" :key="index">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border border-2">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mt-0">{{ totalDataInCategory.name }}</h6>
                                            <div>Informasi Kategori {{ totalDataInCategory.name }}</div>
                                        </div>
                                        <div :class="['widgets-icons', bgColors[index % bgColors.length], textColors[index % textColors.length], 'ms-auto', 'p-3']">
                                            <i class="bx bx-info-circle"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm mb-0">
                                        <tbody>
                                            <tr>
                                                <td><i class='bx bx-edit text-info font-20'></i> Tryout</td>
                                                <td>{{ totalDataInCategory.exam_group_count }}</td>
                                                <td><Link class="badge bg-info" :href="`/admin/exam-groups?category_id=${totalDataInCategory.id}`">Lihat</Link></td>
                                            </tr>
                                            <tr>
                                                <td><i class='bx bx-book text-success font-20'></i> Latihan Soal</td>
                                                <td>{{ totalDataInCategory.exam_count }}</td>
                                                <td><Link class="badge bg-info" :href="`/admin/exams?category_id=${totalDataInCategory.id}`">Lihat</Link></td>
                                            </tr>
                                            <tr>
                                                <td><i class='bx bx-file text-warning font-20'></i> Modul / Materi</td>
                                                <td>{{ totalDataInCategory.module_count }}</td>
                                                <td><Link class="badge bg-info" href="/admin/modules">Lihat</Link></td>
                                            </tr>
                                            <tr>
                                                <td><i class='bx bx-video-recording text-danger font-20'></i> Video Belajar</td>
                                                <td>{{ totalDataInCategory.video_module_count }}</td>
                                                <td><Link class="badge bg-info" href="/admin/video-modules">Lihat</Link></td>
                                            </tr>
                                            <tr>
                                                <td><i class='bx bx-chalkboard text-success font-20'></i> Course</td>
                                                <td>{{ totalDataInCategory.course_count }}</td>
                                                <td><Link class="badge bg-info" href="/admin/courses">Lihat</Link></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
    import LayoutAdmin from '../../../Layouts/Layout.vue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    //import component pagination
    import Pagination from '../../../Components/Pagination.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    export default {
        // layout
        layout: LayoutAdmin,
        // register components
        components: {
            Head,
            Pagination,
            Link
        },
        props: {
            totalStudent: String,
            totalStudentActive: String,
            totalStudentNonActive: String,
            totalStudentMember: String,

            totalTransactionToday: String,
            totalTransactionMonthly: String,
            totalTransactionYearly: String,

            totalTransactionPending: String,
            totalTransactionPaid: String,
            totalTransactionDone: String,
            totalTransactionFailed: String,

            totalQuestionTitleByUser: String,
            totalExamByUser: String,
            totalExamGroupByUser: String,
            totalDataInCategories: Object,
            transactions: Object,
            announcementSummaries: Object,

            // chart: Object
        },
        setup() {
            const bgColors = ['bg-light-primary', 'bg-light-danger', 'bg-light-info', 'bg-light-warning'];
            const textColors = ['text-primary', 'text-danger', 'text-info', 'text-warning'];

            return {
                bgColors,
                textColors,
            };
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
        },
        computed: {
            canDisplayAdminTransactions() {
                const setting = this.$page.props.setting;

                return (
                        setting.enable_practice_question_sales == 1 ||
                        setting.enable_tryout_sales == 1 ||
                        setting.enable_module_material_sales == 1 ||
                        setting.enable_video_module_sales == 1 ||
                        setting.enable_course_sales == 1
                );
            }
        }
    }
</script>
