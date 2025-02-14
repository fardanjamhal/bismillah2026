<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Grup Sosial Media</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Grup Sosial Media</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Grup Sosial Media</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <div class="card radius-10">
                <div class="card-body">
                    <ul class="list-unstyled">
                        <!-- Jika socialGroups memiliki data -->
                        <template v-if="socialGroups && socialGroups.length > 0">
                            <li 
                                class="d-flex flex-column flex-md-row align-items-center text-center text-md-start border-bottom py-3" 
                                v-for="(socialGroup, index) in socialGroups" 
                                :key="index"
                            >
                                <img 
                                    v-bind:src="'/storage/upload_files/social_groups/' + socialGroup.logo" 
                                    class="rounded-circle p-1 border mb-3 mb-md-0" 
                                    width="50" 
                                    height="50" 
                                    alt="..."
                                />
                                <div class="flex-grow-1 ms-md-3">
                                    <h5 class="mt-0 mb-1">{{ socialGroup.name }}</h5>
                                    {{ socialGroup.description }}
                                </div>
                                <div class="mt-3 mt-md-0">
                                    <a class="btn btn-primary btn-sm" :href="socialGroup.link" target="_blank">Bergabung</a>
                                </div>
                            </li>
                        </template>
                        <!-- Jika tidak ada data -->
                        <template v-else>
                            <h5 class="text-center mb-0 p-0">Data Grup Tidak Tersedia</h5>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout user
    import LayoutUser from '../../../Layouts/Layout.vue';

    //import component pagination
    import Pagination from '../../../Components/Pagination.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

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
            socialGroups: Object
        },
    }
</script>
