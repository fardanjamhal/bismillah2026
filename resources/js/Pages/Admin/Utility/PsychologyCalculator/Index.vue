<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Kalkulator Psikologi</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Kalkulator Psikologi</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data Kalkulator Psikologi</li>
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
            <div class="card radius-10 border-top border-0 border-3 border-primary" v-for="(category, index) in categories" :key="index">
                <div class="card-header mt-2">
                    <h6 class="card-title">Kalkulator Psikologi {{ category.name }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" style="font-size:10pt;">
                            <tbody>
                                <tr v-for="(value_category_group, index) in category.value_category_group" :key="index">
                                    <td width="50px">{{ ++index }}</td>
                                    <td>{{ value_category_group.name }}</td>
                                    <td width="50px">
                                        <Link :href="`/admin/psychology-calculators/categories/${category.id}/value-category-groups/${value_category_group.id}/calculate-score`" class="btn btn-primary btn-sm">Hitung Nilai</Link>
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
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    export default {
        // layout
        layout: LayoutAdmin,

        // register components
        components: {
            Link,
            Head,
        },

        // props
        props: {
            categories: Object
        },
    }
</script>
