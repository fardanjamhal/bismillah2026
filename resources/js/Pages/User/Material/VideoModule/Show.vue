<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Video Pembelajaran</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Video Pembelajaran</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ videoModule.category.name }} - {{ videoModule.title }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <Link :href="`/user/video-modules`" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                </div>
            </div>
            <!--end breadcrumb-->
            <hr />
            <div class="row">
                <div class="col-lg-12">
                    <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                        <div v-html="$page.props.session.error"></div>
                    </div>
                    <div v-if="$page.props.session.success" class="alert alert-success border-0">
                        <div v-html="$page.props.session.success"></div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card radius-10 border-start border-0 border-3 border-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-9 col-md-12">
                                    <h5 class="mt-0">{{ videoModule.category.name }} -  {{ videoModule.title }}</h5>
                                    <div v-html="videoModule.description"></div>

                                    <!-- Kategori Member -->
                                    <p class="my-2 text-center text-md-start">
                                        <template v-if="($page.props.setting.video_module_sales_type == 2 || $page.props.setting.video_module_sales_type == 3) && $page.props.auth.user.member_type == 2">
                                            <template v-if="videoModule.member_categories && videoModule.member_categories.length">
                                                <span v-for="(memberCategory, subIndex) in videoModule.member_categories" :key="subIndex" class="badge bg-success me-1">
                                                    {{ memberCategory.name }}
                                                </span>
                                            </template>
                                            <span v-else class="badge bg-success">
                                                Seluruh Member & Non Member
                                            </span>
                                        </template>
                                    </p>
                                </div>
                                <div class="col-lg-3 col-md-12 text-center text-md-end">
                                    <!-- Bagian Harga -->
                                    <div v-if="($page.props.setting.video_module_sales_type == 1 || $page.props.setting.video_module_sales_type == 3) && $page.props.auth.user.member_type == 2">
                                        <div v-if="videoModule.price_type == 2">
                                            <div v-if="videoModule.price_before_discount == videoModule.price_after_discount">
                                                <h6 class="card-price">Rp.{{ formatPrice(videoModule.price_after_discount) }}</h6>
                                            </div>
                                            <div v-else>
                                                <h6 class="text-dark">
                                                    <sup>
                                                        <s>Rp.{{ formatPrice(videoModule.price_before_discount) }}</s>
                                                        <span class="badge bg-danger mx-1">
                                                            {{ formatPrice((videoModule.price_before_discount - videoModule.price_after_discount) / videoModule.price_before_discount * 100)}}%
                                                        </span>
                                                    </sup>
                                                    <br>
                                                    Rp.{{ formatPrice(videoModule.price_after_discount) }}
                                                </h6>
                                            </div>     
                                        </div>      
                                        <div v-else>
                                            <h6 class="text-dark">Gratis</h6>
                                        </div>                             
                                    </div>

                                    <!-- Bagian Tombol -->
                                    <template v-if="$page.props.auth.user.member_type == 2 && $page.props.setting.enable_video_module_sales == 1">
                                        <template v-if="$page.props.setting.video_module_sales_type == 1">
                                            <template v-if="videoModule.user_access.length > 0 || videoModule.price_type == 1">
                                                <span class="badge bg-warning text-dark font-13 m-1">Enrolled</span>
                                            </template>
                                            <template v-else>
                                                <Link class="btn btn-sm btn-danger me-1" :href="`/user/transactions/videoModule/${videoModule.id}/buy`">Beli</Link>
                                            </template>
                                        </template>

                                        <template v-if="$page.props.setting.video_module_sales_type == 2">
                                            <template v-if="checkMemberCategories(videoModule.member_categories) == true">
                                                <span class="badge bg-warning text-dark font-13 m-1">Enrolled</span>
                                            </template>
                                            <template v-else>
                                                <Link :href="`/user/vouchers?category_id=${videoModule.category_id}`" class="btn btn-sm btn-success">
                                                    <span v-if="videoModule.member_categories.length == 1">Upgrade Ke {{ videoModule.member_categories[0] ? videoModule.member_categories[0].name : '' }}</span>
                                                    <span v-else>Upgrade Member</span>
                                                </Link>
                                            </template>
                                        </template>

                                        <template v-if="$page.props.setting.video_module_sales_type == 3">
                                            <template v-if="videoModule.user_access.length > 0 && checkMemberCategories(videoModule.member_categories) == true">
                                                <span class="badge bg-warning text-dark font-13 m-1">Enrolled</span>
                                            </template>
                                            <template v-if="(videoModule.user_access.length == 0  || checkMemberCategories(videoModule.member_categories) == false) && (videoModule.user_access.length > 0 || checkMemberCategories(videoModule.member_categories) == true || videoModule.price_type == 1)">
                                                <span class="badge bg-warning text-dark font-13 m-1">Enrolled</span>
                                            </template>
                                            <template v-if="videoModule.user_access.length == 0 && videoModule.price_type == 2">
                                                <Link class="btn btn-sm btn-danger me-1" :href="`/user/transactions/videoModule/${videoModule.id}/buy`">Beli</Link>
                                            </template>
                                            <template v-if="checkMemberCategories(videoModule.member_categories) == false">
                                                <Link :href="`/user/vouchers?category_id=${videoModule.category_id}`" class="btn btn-sm btn-success">
                                                    <span v-if="videoModule.member_categories.length == 1">Upgrade Ke {{ videoModule.member_categories[0] ? videoModule.member_categories[0].name : '' }}</span>
                                                    <span v-else>Upgrade Member</span>
                                                </Link>
                                            </template>
                                        </template>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card radius-10 border-start border-0 border-3 border-primary">
                        <div class="card-body">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Video Pembelajaran</th>
                                            <th>Diperbarui</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="videoModule.detail_video_module.length > 0" v-for="(detail_video_module, index) in videoModule.detail_video_module" :key="index">
                                            <td>{{ ++index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div><i class='bx bx-video-recording me-2 font-24 text-danger'></i></div>
                                                    <div class="font-weight-bold text-danger">{{ detail_video_module.title }}</div>
                                                </div>
                                            </td>
                                            <td>{{ formatDateWithTimeHourMinute(detail_video_module.created_at) }}</td>
                                            <td><Link :href="`/user/video-modules/${videoModule.id}/detail-video-modules/${detail_video_module.id}`" class="btn btn-outline-primary btn-sm">Lihat</Link></td>
                                        </tr>
                                        <tr v-else>
                                            <td colspan="5" align="center">Video Pembelajaran Belum Tersedia</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
import LayoutUser from '../../../../Layouts/Layout.vue';
import { Link, Head } from '@inertiajs/inertia-vue3';

export default {
    layout: LayoutUser,
    components: {
        Link,
        Head,
    },
    props: {
        videoModule: Object,
        userMemberCategories: Object,
    },
    setup(props) {
        const checkMemberCategories = (categories) => {
            if (categories.length > 0) {
                const categoryIds = categories.map(category => category.id);
                return props.userMemberCategories.some(entry => categoryIds.includes(entry.member_category_id));
            } else {
                return true;
            }
        };

        return {
            checkMemberCategories
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