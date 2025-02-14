<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data User</title>
    </Head>
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">User</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Data User</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center">
                        <Link :href="`/user/users/${user.id}/edit`" class="btn btn-primary btn-sm mt-2 mt-lg-0">Ubah Data</Link>
                        <div class="ms-auto">
                            <a @click.prevent="clearCache()" class="btn btn-danger btn-sm mt-2 mt-lg-0">Hapus Cache</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <br>
                        <div v-if="$page.props.session.error" class="alert alert-danger border-0 alert-dismissible fade show">
                            <i class="fa fa-exclamation-triangle"></i>  {{ $page.props.session.error }}
                        </div>

                        <div v-if="$page.props.session.success" class="alert alert-success border-0 alert-dismissible fade show">
                            <i class="fa fa-exclamation-triangle"></i>  {{ $page.props.session.success }}
                        </div>

                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <th colspan="3"><h6>INFORMASI AKUN</h6></th>
                                </tr>
                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'code')?.translate }}</th>
                                    <td width="10px">:</td>
                                    <td>{{ user.code ?? '-' }}</td>
                                </tr>
                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'name')?.translate }}</th>
                                    <td width="10px">:</td>
                                    <td>{{ user.name ?? '-' }}</td>
                                </tr>
                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'email' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'email')?.translate }}</th>
                                    <td>:</td>
                                    <td>{{ user.email ?? '-' }}</td>
                                </tr>
                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'username' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'username')?.translate }}</th>
                                    <td>:</td>
                                    <td>{{ user.username ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tipe Member</th>
                                    <td>:</td>
                                    <td>{{ user.member_type == 1 ? 'Member Gratis Tryout' : 'Member Tryout Berbayar' }}</td>
                                </tr>
                                <tr>
                                    <th>Status Akun</th>
                                    <td>:</td>
                                    <td>
                                        <span v-if="user.is_active == 1" class="badge bg-success">Active</span>
                                        <span v-else class="badge bg-danger">Non-active</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="3">&nbsp;</th>
                                </tr>
                                <tr v-if="['province_id', 'city_id', 'district_id', 'village_id', 'address', 'gender', 'phone_number']
                                            .every(fieldName => {
                                                const field = $page.props.setting.authentication_field.find(f => f.name === fieldName);
                                                return field && field.is_active == '1';
                                            })">
                                    <th colspan="3"><h6>INFORMASI LAINNYA</h6></th>
                                </tr>

                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'province_id' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'province_id')?.translate }}</th>
                                    <td>:</td>
                                    <td>{{ user.student && user.student.province ? user.student.province.name : '-' }}</td>
                                </tr>
                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'city_id' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'city_id')?.translate }}</th>
                                    <td>:</td>
                                    <td>{{ user.student && user.student.city ? user.student.city.name : '-' }}</td>
                                </tr>
                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'district_id' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'district_id')?.translate }}</th>
                                    <td>:</td>
                                    <td>{{ user.student && user.student.district ? user.student.district.name : '-' }}</td>
                                </tr>
                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'village_id' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'village_id')?.translate }}</th>
                                    <td>:</td>
                                    <td>{{ user.student && user.student.village ? user.student.village.name : '-' }}</td>
                                </tr>
                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'address' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'address')?.translate }}</th>
                                    <td>:</td>
                                    <td>{{ user.student && user.student.address ? user.student.address : '-' }}</td>
                                </tr>
                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'gender' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'gender')?.translate }}</th>
                                    <td>:</td>
                                    <td>{{ user.student ? (user.student.gender == 'M' ? 'Laki-laki' : 'Perempuan') : '-' }}</td>
                                </tr>
                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'phone_number' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'phone_number')?.translate }}</th>
                                    <td>:</td>
                                    <td>{{ user.student && user.student.phone_number ? user.student.phone_number : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <h6>Akses Kategori Member</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Kategori Member</th>
                                    <th>Tanggal Kedaluarsa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(member, index) in user.member_category_user" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td><span class="badge bg-primary">{{ member.category ? member.category.name : '' }}</span></td>
                                    <td><span class="badge bg-success">{{ member.member_category ? member.member_category.name : '' }}</span></td>
                                    <td>{{ formatDate(member.expired_date) }}</td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="4" v-if="!user.member_category_user.length">Data Tidak Tersedia</td>
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
    import LayoutAdmin from '../../../Layouts/Layout.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import moment from 'moment';

    //import sweet alert2
    import Swal from 'sweetalert2';

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
            user: Object,
            verificationLink: String,
            errors: Object,
            session: Object
        },
        setup() {
            const clearCache = () => {
                Swal.fire({
                    title: 'Hapus Cache',
                    text: "Hapus Cache Pada Device Anda.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus'
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        localStorage.removeItem("indexPage");
                        localStorage.removeItem("examId");
                        localStorage.removeItem("myAnswers");
                        localStorage.removeItem("userId");

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Cache Berhasil dihapus.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            return {
                clearCache,
            }
        },
        methods: {
            formatDate: function (date) {
                return moment(date).format('DD MMMM YYYY');
            },
        }
    }
</script>
