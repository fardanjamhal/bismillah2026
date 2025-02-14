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
            <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.error"></div>
            </div>
            <div v-if="$page.props.session.warning" class="alert alert-warning border-0">
                <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.warning"></div>
            </div>
            <div v-if="$page.props.session.success" class="alert alert-success border-0">
                <i class="fa fa-exclamation-triangle"></i> <div v-html="$page.props.session.success"></div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <Link href="/admin/users" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'code')?.translate }}</th>
                                    <td>:</td>
                                    <td>{{ user.code ?? '-' }}</td>
                                </tr>
                                <tr v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">
                                    <th>{{ $page.props.setting.authentication_field.find(field => field.name == 'name')?.translate }}</th>
                                    <td>:</td>
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
                                    <th>Level</th>
                                    <td width="10">:</td>
                                    <td>{{ user.level == 1 ? 'Admin' : 'User' }}</td>
                                </tr>
                                <tr>
                                    <th>Tipe Member</th>
                                    <td>:</td>
                                    <td>{{ user.member_type == 1 ? 'Member Gratis Tryout' : 'Member Tryout Berbayar' }}</td>
                                </tr>
                                <tr>
                                    <th>Created at</th>
                                    <td>:</td>
                                    <td>{{ user.created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Saldo Akun</th>
                                    <td>:</td>
                                    <td>{{ formatPrice(user.account_balance) }}</td>
                                </tr>
                                <tr>
                                    <th>Status Akun</th>
                                    <td>:</td>
                                    <td>
                                        <span v-if="user.is_active == 1" class="badge bg-success">Active</span>
                                        <span v-else class="badge bg-danger">Non-active</span>
                                    </td>
                                </tr>
                                <tr v-if="user.is_active == 0 && user.student">
                                    <th>Kirim Verifikasi Akun</th>
                                    <td>:</td>
                                    <td><Link :href="`/admin/users/${user.id}/send-activation-link`" class="btn btn-success btn-sm">Klik Untuk Mengirim Link Verifikasi</Link></td>
                                </tr>
                                <tr v-if="user.is_active == 0 && user.student">
                                    <th>Kirim Reminder Aktivasi</th>
                                    <td>:</td>
                                    <td><Link :href="`/admin/users/${user.id}/activation-reminder`" class="btn btn-success btn-sm">Kirim Reminder Aktivasi Akun</Link></td>
                                </tr>
                                <tr v-if="user.referrer">
                                    <th>Referrer / Affiliator</th>
                                    <td>:</td>
                                    <td>{{ user.referrer ? user.referrer.name + ' - ' + user.referrer.email : '' }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3">&nbsp;</th>
                                </tr>
                                <tr>
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
                                    <td>{{ user.student && user.student.gender ? (user.student.gender == 'M' ? 'Laki-laki' : 'Perempuan') : '-' }}</td>
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
                    <h6 class="card-title mb-0">Riwayat Login Perangkat</h6>
                </div>
                <div class="card-body">
                    <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>IP Address</th>
                                    <th>Perangkat</th>
                                    <th>Aktivitas Terakhir</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!loginSessions.length">
                                    <td colspan="5" align="center">Tidak Ada Riwayat Login Perangkat</td>
                                </tr>
                                <tr v-for="(loginSession, index) in loginSessions" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td>{{ loginSession.ip_address }}</td>
                                    <td>{{ loginSession.user_agent }}</td>
                                    <td>{{ formatDateLoginSession(loginSession.last_activity) }}</td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <a href="#" @click.prevent="deleteLoginSession(loginSession.id)" class="ms-1"><i class="bx bxs-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <h6 class="card-title mb-0">Akses Kategori Member Aktif (Jika Sistem Pembelian Dengan Membership)</h6>
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(member, index) in user.member_category_user" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td><span class="badge bg-primary">{{ member.category ? member.category.name : '' }}</span></td>
                                    <td><span class="badge bg-success">{{ member.member_category ? member.member_category.name : '' }}</span></td>
                                    <td>{{ formatDate(member.expired_date) }}</td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <a href="#" @click.prevent="deleteMembership(member.id)" class="ms-1"><i class="bx bxs-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="5" v-if="!user.member_category_user.length">Data Tidak Tersedia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <h6 class="card-title mb-0">Akses User Jika Pembelian Per Item</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Peminatan</th>
                                    <th>Type</th>
                                    <th>Judul</th>
                                    <th>Tanggal Dibuat Akses</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!user.user_access.length">
                                    <td colspan="6" align="center">Tidak Ada Akses Soal atau Tryout</td>
                                </tr>
                                <tr v-for="(userAccess, index) in user.user_access" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td><span class="badge bg-primary">{{ userAccess.access.category ? userAccess.access.category.name : '' }}</span></td>
                                    <td>
                                        {{ 
                                            userAccess.access_type == 'App\\Models\\Exam\\Exam' ? 'Latihan Soal' : 
                                            (userAccess.access_type == 'App\\Models\\Exam\\ExamGroup' ? 'Tryout' : 
                                            (userAccess.access_type == 'App\\Models\\Material\\Module' ? 'Modul / Materi' : 
                                            (userAccess.access_type == 'App\\Models\\Material\\VideoModule' ? 'Video Pembelajaran' : 
                                            (userAccess.access_type == 'App\\Models\\Course\\Course' ? 'Course' : '')))) 
                                        }}
                                    </td>
                                    <td>{{ userAccess.access.title }}</td>
                                    <td>{{ formatDateWithTime(userAccess.access.created_at) }}</td>
                                    <td>
                                        <div class="d-flex order-actions">
                                            <a href="#" @click.prevent="deleteUserAccess(userAccess.id)" class="ms-1"><i class="bx bxs-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <h6 class="card-title mb-0">Riwayat Latihan Soal</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Peminatan</th>
                                    <th>Kategori Mata Pelajaran</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Judul Tryout</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!grades.length">
                                    <td colspan="8" align="center">Tidak Ada Riwayat Latihan Soal</td>
                                </tr>
                                <tr v-for="(grade, index) in grades" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td><span class="badge bg-primary">{{ grade.category.name }}</span></td>
                                    <td>{{ grade.lesson_category.name }}</td>
                                    <td>{{ grade.lesson.name }}</td>
                                    <td>{{ grade.exam.title }}</td>
                                    <td>{{ formatDateWithTime(grade.start_time) }}</td>
                                    <td>{{ formatDateWithTime(grade.end_time) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <h6 class="card-title mb-0">Riwayat Tryout</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Peminatan</th>
                                    <th>Kategori</th>
                                    <th>Judul Tryout</th>
                                    <th>Tanggal Pengerjaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!histories.length">
                                    <td colspan="8" align="center">Tidak Ada Riwayat Tryout</td>
                                </tr>
                                <tr v-for="(history, index) in histories" :key="index">
                                    <td>{{ ++index }}</td>
                                    <td><span class="badge bg-primary">{{ history.exam_group.category.name }}</span></td>
                                    <td><span class="badge bg-primary">{{ history.exam_group.lesson_category.name }}</span></td>
                                    <td>{{ history.exam_group.title }}</td>
                                    <td>{{ formatDate(history.created_at) }}</td>
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

    //import sweet alert2
    import Swal from 'sweetalert2';

    import { Inertia } from '@inertiajs/inertia';

    import moment from 'moment';
    
    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

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
            Pagination
        },

        // props
        props: {
            user: Object,
            grades: Object,
            histories: Object,
            verificationLink: String,
            loginSessions: Object,
            errors: Object,
            session: Object
        },
        setup(props) {
            const deleteLoginSession = (id) => {
                Swal.fire({
                    title: 'Apakah Anda yakin ?',
                    text: "Menghapus data ini dapat menyebabkan akun Anda keluar dari perangkat yang sedang digunakan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/admin/users/${props.user.id}/sessions/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Perangkat Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            const deleteMembership = (id) => {
                Swal.fire({
                    title: 'Apakah Anda yakin ?',
                    text: "Menghapusnya dapat menyebabkan hilangnya akses peserta ke membership.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/admin/users/${props.user.id}/user-member-categories/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Membership Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            const deleteUserAccess = (id) => {
                Swal.fire({
                    title: 'Apakah Anda yakin ?',
                    text: "Menghapusnya dapat menyebabkan hilangnya akses pada latihan soal dan tryout.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/admin/users/${props.user.id}/user-access/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Akses Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            return {
                deleteMembership,
                deleteUserAccess,
                deleteLoginSession
            }
        },
        methods: {
            formatPrice(value) {
                let val = (value/1).toFixed().replace('.', ',')
                return 'Rp.' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            formatDate: function (date) {
                return moment(date).format('DD MMMM YYYY');
            },
            formatDateLoginSession: function (timestamp) {
                return moment.unix(timestamp).format('DD-MM-YYYY HH:mm:ss');
            },
        }
    }
</script>
