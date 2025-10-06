<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Dashboard</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--
            <div v-if="canDisplayTransactions" class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
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
            </div>
            -->

            <div>
                <div class="card-body">
                    <!--
                    <h6 class="mb-3">Informasi Kategori Peminatan</h6>
                    -->
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4" v-for="(totalDataInCategory, index) in totalDataInCategories" :key="index">
                            <div>
                                
                                <!--
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
                                -->
                                
                                <!--
                                <div class="card-body">
                                    <table class="table table-sm mb-0">
                                        <tbody>
                                            <tr v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'tryout')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'tryout'))">
                                                <td><i class='bx bx-edit text-info font-20'></i> Tryout</td>
                                                <td>{{ totalDataInCategory.exam_group_count }}</td>
                                                <td><Link class="badge bg-info" :href="`/user/exam-groups/${totalDataInCategory.id}/lesson-categories`">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'question_practice')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'question_practice'))">
                                                <td><i class='bx bx-book text-success font-20'></i> Latihan Soal</td>
                                                <td>{{ totalDataInCategory.exam_count }}</td>
                                                <td><Link class="badge bg-info" :href="`/user/categories/${totalDataInCategory.id}/lesson-categories`">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'module')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'module'))">
                                                <td><i class='bx bx-file text-warning font-20'></i> Modul / Materi</td>
                                                <td>{{ totalDataInCategory.module_count }}</td>
                                                <td><Link class="badge bg-info" href="/user/modules">Lihat</Link></td>
                                            </tr>
                                            <tr v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'video_module')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'video_module'))">
                                                <td><i class='bx bx-video-recording text-danger font-20'></i> Video Belajar</td>
                                                <td>{{ totalDataInCategory.video_module_count }}</td>
                                                <td><Link class="badge bg-info" href="/user/video-modules">Lihat</Link></td>
                                            </tr>

                                            <tr v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'course')) || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'course'))">
                                                <td><i class='bx bx-chalkboard text-success font-20'></i> Course</td>
                                                <td>{{ totalDataInCategory.course_count }}</td>
                                                <td><Link class="badge bg-info" href="/user/courses">Lihat</Link></td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                </div>
                                -->

                            </div>
                        </div>
                    </div>
                </div>


    

                <div class="card-body">
                    <div class="row g-4">
                        <template v-for="(category, index) in totalDataInCategories.slice(0, 4)" :key="index">
                            
                            <div class="col-12">
                                <div class="row g-4">
                                    
                                    <!-- ✅ TRYOUT -->
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-4"
                                    v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'tryout')) 
                                    || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'tryout'))">

                                    <div class="card rounded-4 h-100"
                                        style="background: linear-gradient(135deg, #6f42c1, #8a5bda); color: #fff; border: none; transition: all 0.3s; cursor: pointer;"
                                        @mouseover="event.currentTarget.style.transform='translateY(-5px)'; event.currentTarget.style.boxShadow='0 8px 20px rgba(111,66,193,0.4)'"
                                        @mouseleave="event.currentTarget.style.transform='translateY(0)'; event.currentTarget.style.boxShadow='none'">
                                        
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <a :href="`/user/exam-groups/${category.id}/lesson-categories`">
                                                <div style="width: 55px; height: 55px; display:flex; justify-content:center; align-items:center; margin-right:15px;
                                                            border-radius:50%; background:rgba(255,255,255,0.25);">
                                                    <i class="bx bx-edit" style="font-size:1.6rem; color:#fff;"></i>
                                                </div>
                                                </a>
                                                <div class="flex-grow-1 text-end">
                                                    <h4 class="my-1 fw-bolder" style="font-size:2rem; color:white;">{{ category.exam_group_count }}</h4>
                                                    <p class="mb-0" style="opacity:0.9;">Tryout</p>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-2 text-end" style="border-top:1px dashed rgba(255,255,255,0.3);">
                                                <Link :href="`/user/exam-groups/${category.id}/lesson-categories`" 
                                                    class="fw-bold text-decoration-none"
                                                    style="color:#fff; font-size:0.9rem;">
                                                    Lihat Tryout <i class="bx bx-right-arrow-alt"></i>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ✅ LATIHAN SOAL -->
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-4"
                                    v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'question_practice')) 
                                    || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'question_practice'))">

                                    <div class="card rounded-4 h-100"
                                        style="background: linear-gradient(135deg, #20c997, #38e0b0); color:#fff; border:none; transition:all 0.3s; cursor:pointer;"
                                        @mouseover="event.currentTarget.style.transform='translateY(-5px)'; event.currentTarget.style.boxShadow='0 8px 20px rgba(32,201,151,0.4)'"
                                        @mouseleave="event.currentTarget.style.transform='translateY(0)'; event.currentTarget.style.boxShadow='none'">

                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <a :href="`/user/categories/${category.id}/lesson-categories`">
                                                <div style="width:55px; height:55px; display:flex; justify-content:center; align-items:center; margin-right:15px;
                                                            border-radius:50%; background:rgba(255,255,255,0.25);">
                                                    <i class="bx bx-book" style="font-size:1.6rem; color:#fff;"></i>
                                                </div>
                                                </a>
                                                <div class="flex-grow-1 text-end">
                                                    <h4 class="my-1 fw-bolder" style="font-size:2rem; color:white;">{{ category.exam_count }}</h4>
                                                    <p class="mb-0" style="opacity:0.9;">Latihan Soal</p>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-2 text-end" style="border-top:1px dashed rgba(255,255,255,0.3);">
                                                <Link :href="`/user/categories/${category.id}/lesson-categories`" 
                                                    class="fw-bold text-decoration-none"
                                                    style="color:#fff; font-size:0.9rem;">
                                                    Lihat Soal <i class="bx bx-right-arrow-alt"></i>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ✅ MODUL / MATERI -->
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-4"
                                    v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'module')) 
                                    || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'module'))">

                                    <div class="card rounded-4 h-100"
                                        style="background: linear-gradient(135deg, #fd7e14, #ffa54d); color:#fff; border:none; transition:all 0.3s; cursor:pointer;"
                                        @mouseover="event.currentTarget.style.transform='translateY(-5px)'; event.currentTarget.style.boxShadow='0 8px 20px rgba(253,126,20,0.4)'"
                                        @mouseleave="event.currentTarget.style.transform='translateY(0)'; event.currentTarget.style.boxShadow='none'">

                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <a href="/user/modules">
                                                <div style="width:55px; height:55px; display:flex; justify-content:center; align-items:center; margin-right:15px;
                                                            border-radius:50%; background:rgba(255,255,255,0.25);">
                                                    <i class="bx bx-file" style="font-size:1.6rem; color:#fff;"></i>
                                                </div>
                                                </a>
                                                <div class="flex-grow-1 text-end">
                                                    <h4 class="my-1 fw-bolder" style="font-size:2rem; color:white;">{{ category.module_count }}</h4>
                                                    <p class="mb-0" style="opacity:0.9;">Modul / Materi</p>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-2 text-end" style="border-top:1px dashed rgba(255,255,255,0.3);">
                                                <Link href="/user/modules" 
                                                    class="fw-bold text-decoration-none"
                                                    style="color:#fff; font-size:0.9rem;">
                                                    Lihat Modul <i class="bx bx-right-arrow-alt"></i>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ✅ VIDEO BELAJAR -->
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-4"
                                    v-if="($page.props.auth.user.member_type == 1 && $page.props.setting.free_member_access.some(item => item.code === 'video_module')) 
                                    || ($page.props.auth.user.member_type == 2 && $page.props.setting.paid_member_access.some(item => item.code === 'video_module'))">

                                    <div class="card rounded-4 h-100"
                                        style="background: linear-gradient(135deg, #0d6efd, #409cff); color:#fff; border:none; transition:all 0.3s; cursor:pointer;"
                                        @mouseover="event.currentTarget.style.transform='translateY(-5px)'; event.currentTarget.style.boxShadow='0 8px 20px rgba(13,110,253,0.4)'"
                                        @mouseleave="event.currentTarget.style.transform='translateY(0)'; event.currentTarget.style.boxShadow='none'">
                                        
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <a href="/user/video-modules">
                                                <div style="width:55px; height:55px; display:flex; justify-content:center; align-items:center; margin-right:15px;
                                                            border-radius:50%; background:rgba(255,255,255,0.25);">
                                                    <i class="bx bx-video-recording" style="font-size:1.6rem; color:#fff;"></i>
                                                </div>
                                                </a>
                                                <div class="flex-grow-1 text-end">
                                                    <h4 class="my-1 fw-bolder" style="font-size:2rem; color:white;">{{ category.video_module_count }}</h4>
                                                    <p class="mb-0" style="opacity:0.9;">Video Belajar</p>
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-2 text-end" style="border-top:1px dashed rgba(255,255,255,0.3);">
                                                <Link href="/user/video-modules" 
                                                    class="fw-bold text-decoration-none"
                                                    style="color:#fff; font-size:0.9rem;">
                                                    Lihat Video <i class="bx bx-right-arrow-alt"></i>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                </div>
                            </div>
                            
                    <div class="col-12"><hr v-if="index < totalDataInCategories.slice(0, 4).length - 1"></div> 

                    </template>
                    </div>
                </div>


            </div>

            <div class="card" v-if="banners.length > 0">
                <Splide :options="{ 
                        type: 'loop',
                        perPage: 1,  
                        perMove: 1,   
                        autoplay: true,
                        direction: 'ltr',
                        pauseOnHover: true,
                        pauseOnFocus: true,
                        interval: 5000 
                    }" 
                    aria-label="My Favorite Images">
                    <SplideSlide v-for="(banner, index) in banners" :key="index">
                        <img v-bind:src="'/storage/upload_files/banners/' + banner.image" :alt="banner.name" style="width:100%; cursor: pointer;" @click="showPopup(banner)">
                    </SplideSlide>
                </Splide>
            </div>

            <div v-if="canDisplayTransactions" class="card radius-10 border-start border-0 border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-3">Riwayat Transaksi Terbaru</h6>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="transactions.length" v-for="(transaction, index) in transactions" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td>{{ transaction.code }}</td>
                                    <td>{{ transaction.description }}</td>
                                    <td>{{ transaction.created_at }}</td>
                                    <td>{{ formatPrice(transaction.total_payment) }}</td>
                                    <td>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'account_balance'">Saldo Akun</span>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'automatic_transfer_midtrans'">Transfer Otomatis</span>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'manual_transfer'">Transfer Manual</span>
                                        <span class="badge bg-primary" v-if="transaction.payment_method == 'not_payment_method'">Tanpa Metode Pembayaran</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning" v-if="transaction.transaction_status == 'pending'">Pending</span>
                                        <span class="badge bg-primary" v-if="transaction.transaction_status == 'paid'">Paid</span>
                                        <span class="badge bg-danger" v-if="transaction.transaction_status == 'expired'">Expired</span>
                                        <span class="badge bg-danger" v-if="transaction.transaction_status == 'failed'">Failed</span>
                                        <span class="badge bg-success" v-if="transaction.transaction_status == 'done'">Done</span>
                                    </td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <Link :href="`/user/transactions/${transaction.id}`"><i class='bx bx-grid-alt'></i></Link>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else>
                                    <td colspan="8" align="center">Belum ada transaksi</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

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
                                    <td>
                                        <div class="d-flex order-actions">
                                            <Link :href="`/user/announcements/${announcementSummary.id}`" class="ms-2"><i class='bx bx-grid-alt'></i></Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutUser from '../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import sweet alert2
    import Swal from 'sweetalert2';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { Splide, SplideSlide } from '@splidejs/vue-splide';
    import '@splidejs/vue-splide/css';
    import '@splidejs/vue-splide/css/skyblue';
    import '@splidejs/vue-splide/css/sea-green';

    export default {
        // layout
        layout: LayoutUser,
        // register components
        components: {
            Head,
            Link,
            Splide,
            SplideSlide,
        },
        // props
        props: {
            banners: Object,
            totalTransactionPending: Object,
            totalTransactionPaid: Object,
            totalTransactionDone: Object,
            totalTransactionFailed: Object,
            transactions: Object,
            gradeSummaries: Object,
            announcementSummaries: Object,
            totalDataInCategories: Object,
        },
        setup() {
            const bgColors = ['bg-light-primary', 'bg-light-danger', 'bg-light-info', 'bg-light-warning'];
            const textColors = ['text-primary', 'text-danger', 'text-info', 'text-warning'];

            const showPopup = (banner) => {
                Swal.fire({
                    imageUrl: '/storage/upload_files/banners/' + banner.image,
                    imageAlt: banner.name,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: '100%',
                    customClass: {
                        popup: 'custom-popup', // Optional for additional customization
                    },
                });
            }

            return {
                bgColors,
                textColors,
                showPopup
            };
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
        },
        computed: {
            canDisplayTransactions() {
                const { transactions } = this;
                const user = this.$page.props.auth.user;
                const settings = this.$page.props.setting;

                return (
                    transactions &&
                    user.member_type === 2 &&
                    (
                        settings.enable_practice_question_sales === 1 ||
                        settings.enable_tryout_sales === 1 ||
                        settings.enable_module_material_sales === 1 ||
                        settings.enable_video_module_sales === 1 ||
                        settings.enable_course_sales === 1
                    )
                );
            }
        }
    }
</script>

<style>
    .splide {
        padding: 10px !important;
        margin: 0 !important;
    }

    .custom-popup {
        border-radius: 10px;
    }

    .swal2-close {
        font-size: 32px; 
        width: 32px;    
        height: 32px;    
        line-height: 32px;
        padding: 4px;    
        outline: none;
        background: none;
        box-shadow: none;
        transition: none;
    }

    .swal2-close:active,
    .swal2-close:focus {
        outline: none;
        background: none;
        box-shadow: none;
        color: inherit;
    }
</style>