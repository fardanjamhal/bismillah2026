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
                            <li class="breadcrumb-item active" aria-current="page">Data Video Pembelajaran</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <hr />
            <div v-if="!categoryVideoModules.length">
                <div class="row row-cols-12 row-cols-md-12 row-cols-lg-12 row-cols-xl-12">
                    <div class="col">
                        <div class="card border-bottom border-3 border-0">
                            <div class="card-body">
                                <h5 class="card-title text-center">Data Video Pembelajaran Belum Tersedia</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="card" v-for="category in categoryVideoModules" :key="category.id">

                <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                    <h6 class="text-white mb-0">
                        Video Pembelajaran {{ category.name }}
                    </h6>
                    <a>
                        <i class="text-white btn btn-danger btn-sm" @click="toggleCollapse(category.id)" :class="collapseStatus[category.id] ? 'bx bx-chevron-up' : 'bx bx-chevron-down'"></i>
                    </a>
                </div>
                <transition name="fade" mode="out-in">
                    <div v-show="collapseStatus[category.id]" :id="'collapse'+category.id" class="collapse show">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4" v-for="videoModule in category.video_module" :key="videoModule.id">
                                    <div class="card radius-10 overflow-hidden shadow-none border border-1">
                                        <div v-if="$page.props.auth.user.member_type == 2 && $page.props.setting.enable_video_module_sales == 1">
                                            <tempate v-if="$page.props.setting.video_module_sales_type == 1">
                                                <span v-if="(videoModule.user_access.length > 0)" class="badge bg-warning text-dark" style="position:absolute;">Enrolled</span>
                                            </tempate>
                                            <tempate v-if="$page.props.setting.video_module_sales_type == 2">
                                                <span v-if="checkMemberCategories(videoModule.member_categories) == true" class="badge bg-warning text-dark" style="position:absolute;">Enrolled</span>
                                            </tempate>
                                            <tempate v-if="$page.props.setting.video_module_sales_type == 3">
                                                <span v-if="(videoModule.user_access.length > 0 || checkMemberCategories(videoModule.member_categories) == true)" class="badge bg-warning text-dark" style="position:absolute;">Enrolled</span>
                                            </tempate>
                                        </div>

                                        <div class="card-header mt-1">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6>{{ videoModule.title }}</h6>
                                                </div>
                                                <div class="widgets-icons ms-auto text-danger bg-light-danger p-3">
                                                    <i class="bx bx-video-recording"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div style="min-height: 96px;">
                                                <p class="mb-0 text-secondary font-14">{{ videoModule.description }}</p>
                                            </div>
                                        </div>
                                        <div class="card-footer text-center">
                                            <Link v-if="videoModule.status == 'active'" :href="`/user/video-modules/${videoModule.id}`" class="btn btn-success btn-sm">Lihat Detail Video</Link>
                                            <span v-else class="badge" :class="{ 'bg-danger': videoModule.status == 'inactive', 'bg-warning': videoModule.status === 'inprogress'}">
                                                {{ videoModule.status === 'inactive' ? 'Inactive' : 'In Progress' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
            <!--end row-->
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
import LayoutUser from '../../../../Layouts/Layout.vue';
import { Link, Head } from '@inertiajs/inertia-vue3';
import { ref, onMounted } from 'vue';

export default {
    layout: LayoutUser,
    components: {
        Link,
        Head,
    },
    props: {
        categoryVideoModules: Object,
        userMemberCategories: Object
    },
    setup(props) {
        const collapseStatus = ref({});

        onMounted(() => {
            props.categoryVideoModules.forEach(category => {
                collapseStatus.value[category.id] = true; // Open by default
            });
        });

        const toggleCollapse = (categoryId) => {
            collapseStatus.value[categoryId] = !collapseStatus.value[categoryId];
        };

        const checkMemberCategories = (categories) => {
            if (categories.length > 0) {
                const categoryIds = categories.map(category => category.id);
                return props.userMemberCategories.some(entry => categoryIds.includes(entry.member_category_id));
            } else {
                return true;
            }
        };

        return {
            collapseStatus,
            toggleCollapse,
            checkMemberCategories
        };
    }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
