<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Tryout</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Tryout</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Soal Tryout</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <Link :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories`" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                </div>
            </div>
            <!--end breadcrumb-->
            <hr/>
            <div class="card">
                <div class="card-body bg-light p-3 rounded-3">
                    <div class="input-group flex-md-nowrap flex-wrap">
                        <input type="text" v-model="form.search" class="form-control form-control-sm sm-2" placeholder="Judul Tryout ....">

                        <select v-model="form.sub_category_id" class="form-control form-control-sm sm-2">
                            <option value="">[ Kategori ]</option>
                            <option v-for="(category, index) in subCategories" :key="index" :value="category.id">
                                {{ category.name }}</option>
                        </select>

                        <button @click.prevent="handleSearch" class="btn btn-primary btn-sm"><i class="bx bx-filter"></i></button>
                        <Link :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories/${lessonCategory.id}/exams`" class="btn btn-danger btn-sm"><i class="bx bx-refresh"></i></Link>
                    </div>
                </div>
            </div>

            <div class="card mb-0">
                <div class="card-body p-3">
                    <div class="list-group">
                        <div v-if="examGroups.data.length" class="list-group-item list-group-item-action p-2 mb-2 border" v-for="(exam, index) in examGroups.data" :key="index">
                            <div class="d-flex flex-column flex-md-row">
                                <!-- Judul, Deskripsi, Kategori, dan Sub Kategori -->
                                <div class="flex-grow-1 text-center text-sm-start">
                                    <h6 class="my-2">{{ exam.title }}</h6>
                                    
                                    <p class="mb-1">
                                        <template v-if="exam.sub_categories && exam.sub_categories.length">
                                            <span v-for="(subCategory, subIndex) in exam.sub_categories" :key="subIndex" class="badge bg-danger me-1">
                                                {{ subCategory.name }}
                                            </span>
                                        </template>
                                        <span v-else class="badge bg-danger">
                                            Seluruh Peminatan {{ exam.category.name }}
                                        </span>
                                    </p>

                                    <!-- Kategori Member -->
                                    <p class="mb-1">
                                        <template v-if="($page.props.setting.tryout_sales_type == 2 || $page.props.setting.tryout_sales_type == 3) && $page.props.auth.user.member_type == 2">
                                            <template v-if="exam.member_categories && exam.member_categories.length">
                                                <span v-for="(memberCategory, subIndex) in exam.member_categories" :key="subIndex" class="badge bg-success me-1">
                                                    {{ memberCategory.name }}
                                                </span>
                                            </template>
                                            <span v-else class="badge bg-success">
                                                Seluruh Member & Non Member
                                            </span>
                                        </template>
                                    </p>
                                </div>
                                
                                <!-- Action Links -->
                                <div class="d-flex flex-column action-button-exams text-center text-md-end">
                                    <div v-if="$page.props.auth.user.member_type == 2">
                                        <tempate v-if="$page.props.setting.tryout_sales_type == 1 && exam.user_access.length > 0">
                                            <span class="badge bg-success" style="position:absolute; left:-10px;top:-5px; font-size:10px; z-index:2;">Enrolled</span>
                                        </tempate>
                                        <tempate v-if="$page.props.setting.tryout_sales_type == 2 && exam.member_categories.length > 0 && checkMemberCategories(exam.member_categories) == true">
                                            <span class="badge bg-success" style="position:absolute; left:-10px;top:-5px; font-size:10px; z-index:2;">Enrolled</span>
                                        </tempate>
                                        <tempate v-if="$page.props.setting.tryout_sales_type == 3 && (exam.user_access.length > 0 || checkMemberCategories(exam.member_categories) == true)">
                                            <span class="badge bg-success" style="position:absolute; left:-10px;top:-5px; font-size:10px; z-index:2;">Enrolled</span>
                                        </tempate>
                                    </div>
                                    <div v-if="($page.props.setting.tryout_sales_type == 1 || $page.props.setting.tryout_sales_type == 3) && $page.props.auth.user.member_type == 2">
                                        <div v-if="exam.price_type == 2">
                                            <div v-if="exam.price_before_discount == exam.price_after_discount">
                                                <h6 class="card-price">Rp.{{ formatPrice(exam.price_after_discount) }}</h6>
                                            </div>
                                            <div v-else>
                                                <h6 class="text-dark">
                                                    <sup>
                                                        <s>Rp.{{ formatPrice(exam.price_before_discount) }}</s>
                                                        <span class="badge bg-danger mx-1">
                                                            {{ formatPrice((exam.price_before_discount - exam.price_after_discount) / exam.price_before_discount * 100)}}%
                                                        </span>
                                                    </sup>
                                                    <br>
                                                    Rp.{{ formatPrice(exam.price_after_discount) }}
                                                </h6>
                                            </div>     
                                        </div>                               
                                    </div>
                                    <div v-if="($page.props.setting.tryout_sales_type == 1 && exam.price_type == 1) || ($page.props.setting.tryout_sales_type == 2 && exam.member_categories.length == 0) || ($page.props.setting.tryout_sales_type == 3 && (exam.member_categories.length == 0 || exam.price_type == 1)) ">
                                        <span class="badge bg-warning text-dark" style="position:absolute; left:-10px;top:-5px; font-size:10px; z-index:1;">Gratis</span>
                                    </div> 

                                    <!-- Bagian Tombol -->
                                    <div>
                                        <div v-if="exam.exam_status == 'active'">
                                            <template v-if="$page.props.auth.user.member_type == 2 && $page.props.setting.enable_tryout_sales == 1">
                                                <template v-if="$page.props.setting.tryout_sales_type == 1">
                                                    <template v-if="exam.user_access.length > 0 || exam.price_type == 1">
                                                        <Link :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories/${lessonCategory.id}/exams/${exam.id}`" class="btn btn-sm btn-primary">Kerjakan</Link>
                                                    </template>
                                                    <template v-else>
                                                        <Link class="btn btn-sm btn-danger me-1" :href="`/user/transactions/examGroup/${exam.id}/buy`">Beli</Link>
                                                    </template>
                                                </template>

                                                <template v-if="$page.props.setting.tryout_sales_type == 2">
                                                    <template v-if="checkMemberCategories(exam.member_categories) == true">
                                                        <Link :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories/${lessonCategory.id}/exams/${exam.id}`" class="btn btn-sm btn-primary me-1">Kerjakan</Link>
                                                    </template>
                                                    <template v-else>
                                                        <Link :href="`/user/vouchers?category_id=${exam.category_id}`" class="btn btn-sm btn-success">
                                                            <span v-if="exam.member_categories.length == 1">Upgrade Ke {{ exam.member_categories[0].name }}</span>
                                                            <span v-else>Upgrade Member</span>
                                                        </Link>
                                                    </template>
                                                </template>

                                                <template v-if="$page.props.setting.tryout_sales_type == 3">
                                                    <template v-if="exam.user_access.length > 0 && checkMemberCategories(exam.member_categories) == true">
                                                        <Link :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories/${lessonCategory.id}/exams/${exam.id}`" class="btn btn-sm btn-primary">Kerjakan</Link>
                                                    </template>
                                                    <template v-if="(exam.user_access.length == 0  || checkMemberCategories(exam.member_categories) == false) && (exam.user_access.length > 0 || checkMemberCategories(exam.member_categories) == true || exam.price_type == 1)">
                                                        <Link :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories/${lessonCategory.id}/exams/${exam.id}`" class="btn btn-sm btn-primary me-1">Kerjakan</Link>
                                                    </template>
                                                    <template v-if="exam.user_access.length == 0 && exam.price_type == 2">
                                                        <Link class="btn btn-sm btn-danger me-1" :href="`/user/transactions/examGroup/${exam.id}/buy`">Beli</Link>
                                                    </template>
                                                    <template v-if="checkMemberCategories(exam.member_categories) == false">
                                                        <Link :href="`/user/vouchers?category_id=${exam.category_id}`" class="btn btn-sm btn-success">
                                                            <span v-if="exam.member_categories.length == 1">Upgrade Ke {{ exam.member_categories[0].name }}</span>
                                                            <span v-else>Upgrade Member</span>
                                                        </Link>
                                                    </template>
                                                </template>
                                            </template>
                                            <template v-else>
                                                <Link :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories/${lessonCategory.id}/exams/${exam.id}`" class="btn btn-sm btn-primary me-1">Kerjakan</Link>
                                            </template>
                                        </div>
                                        <template v-else>
                                            <span class="badge" :class="{ 'bg-danger': exam.exam_status == 'inactive', 'bg-warning': exam.exam_status === 'inprogress'}">
                                                {{ exam.exam_status === 'inactive' ? 'Inactive' : 'In Progress' }}
                                            </span>
                                            <template v-if="exam.exam_status == 'inprogress' && exam.release_date"> 
                                                <br>
                                                <span class="badge bg-success">Rilis {{ formatDateWithTimeHourMinute(exam.release_date) }}</span>
                                            </template>
                                        </template>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div v-else class="list-group-item list-group-item-action order">
                            <h6 class="text-center">Tryout Tidak Tersedia</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3" v-if="examGroups.data.length">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-center" style="min-height: 0vh;">
                        <Pagination :links="examGroups.links"/>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutUser from '../../../../Layouts/Layout.vue';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

    //import sweet alert2
    import Swal from 'sweetalert2';

    //import reactive
    import { reactive } from 'vue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    //import ref from vue
    import { ref } from 'vue';

    export default {
        // layout
        layout: LayoutUser,

        // register components
        components: {
            Link,
            Head,
            Pagination
        },

        // props
        props: {
            examGroups: Object,
            lessonCategory: Object,
            userMemberCategories: Object,
            subCategories: Object,
            session: Object,
        },
        setup(props) {

            const form = reactive({
                search: ref("" || (new URL(document.location)).searchParams.get('search')),
                sub_category_id: ref((new URL(document.location)).searchParams.get('sub_category_id') || ''),
            });

            const handleSearch = () => {
                Inertia.get(`/user/exam-groups/${props.lessonCategory.category_id}/lesson-categories/${props.lessonCategory.id}/exams`, {
                    search: form.search,
                    sub_category_id: form.sub_category_id,
                })
            }

            if (props.session && props.session.success) {
                Swal.fire({
                    title: "Sukses",
                    text: props.session.success,
                    icon: "success",
                })
            }

            if (props.session && props.session.error) {
                Swal.fire({
                    title: "Peringatan",
                    text: props.session.error,
                    icon: "error",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Top Up Saldo',
                    cancelButtonText: 'Tidak'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        Inertia.get('/user/account-balances');
                    }
                })
            }

            const checkMemberCategories = (categories) => {
                if (categories.length > 0) {
                    const categoryIds = categories.map(category => category.id);
                    return props.userMemberCategories.some(entry => categoryIds.includes(entry.member_category_id));
                } else {
                    return true;
                }
            };

            return {
                form,
                checkMemberCategories,
                handleSearch
            }
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed().replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            }
        }
    }
</script>

<style>
   .action-button-exams {
        display: flex;
        flex-direction: column;
        align-items: flex-end; /* Default alignment for larger screens */
    }

    @media (max-width: 740px) {
        .list-group-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .list-group-item .flex-grow-1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .action-button-exams {
            align-items: center;
            text-align: center;
            margin: 0 auto; /* Ensure the container is centered */
        }

        .action-button-exams h6,
        .action-button-exams .btn,
        .action-button-exams .btn-danger {
            margin: 5px 0; /* Ensure spacing around elements */
        }
    }
</style>