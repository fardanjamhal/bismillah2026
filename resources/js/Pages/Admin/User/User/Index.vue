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

            <div v-if="$page.props.session.success" class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-white"><i class='bx bxs-check-circle'></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 text-white">Sukses</h6>
                        <div class="text-white">{{ $page.props.session.success }}</div>
                    </div>
                </div>
            </div>
            
            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Filter Data</h6>
                        </div>
                    </div>
                </div>

                <div class="card-body"> <!-- Change 'text-center' to 'text-end' -->
                    <form>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 mb-1">
                                <label for="search">Cari Data User</label>
                                <input type="text" v-model="form.search" class="form-control form-control-sm" id="search" placeholder="Cari....">
                            </div>

                            <div class="col-lg-2 col-md-6 mb-1">
                                <label for="lesson_id">Status User</label>
                                <select v-model="form.status_active" class="form-select form-select-sm">
                                    <option value="">[ Pilih ]</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6 mb-1">
                                <label for="category">Level</label>
                                <select v-model="form.level" class="form-select form-select-sm">
                                    <option value="">[ Pilih]</option>
                                    <option value="1">Admin</option>
                                    <option value="3">Guru</option>
                                    <option value="2">Peserta</option>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6 mb-1">
                                <label for="lesson_id">Tipe Member</label>
                                    <select v-model="form.member_type" class="form-select form-select-sm">
                                    <option value="">[ Pilih ]</option>
                                    <option value="1">Gratis</option>
                                    <option value="2">Berbayar</option>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6 mb-1">
                                <label for="end_date">Action</label><br>
                                <button @click.prevent="handleSearch" class="btn btn-primary btn-sm me-2">
                                    <i class="bx bx-filter"></i>Filter
                                </button>

                                <Link href="/admin/users" class="btn btn-danger btn-sm me-2">
                                    <i class="bx bx-refresh"></i>reset
                                </Link>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

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
            
            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">User</h6>
                        </div>
                        <div class="ms-auto">
                            <a href="/admin/users/export" class="btn btn-success btn-sm mt-2 mt-lg-0" target="_blank"><i class="bx bx-download"></i> Export User</a>
                            <Link href="/admin/users/import" class="btn btn-success btn-sm mt-2 mx-2 mt-lg-0"><i class="bx bx-upload"></i> Import User</Link>
                            <Link href="/admin/users/create" class="btn btn-primary btn-sm mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i> Tambah User</Link>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0" style="font-size:10pt;">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'name')?.translate }}</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'code')?.translate }}</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'email' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'email')?.translate }}</th>
                                    <th v-if="$page.props.setting.authentication_field.some(field => field.name == 'username' && field.is_active == '1')">{{ $page.props.setting.authentication_field.find(field => field.name == 'username')?.translate }}</th>
                                    <th>Level</th>
                                    <th>Login</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user, index) in users.data" :key="index">
                                    <td>{{ ++index + (users.current_page - 1) * users.per_page }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">
                                        <div class="d-flex align-items-center">
                                            <!-- <div class="chat-user-online">
                                                <img src="/assets/images/avatars/single-icon-not-found.png" width="45" height="45" class="rounded-circle" alt="">
                                            </div> -->
                                            <div class="flex-grow-1 ms-2">
                                                <p class="mb-0">{{ user.name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">{{ user.code ?? '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'email' && field.is_active == '1')">{{ user.email ?? '-' }}</td>
                                    <td v-if="$page.props.setting.authentication_field.some(field => field.name == 'username' && field.is_active == '1')">{{ user.username ?? '-' }}</td>
                                    <td>
                                        <span v-if="user.level == 1" class="badge bg-success">Admin</span>
                                        <span v-if="user.level == 2" class="badge bg-primary">Peserta</span>
                                        <span v-if="user.level == 3" class="badge bg-danger">Guru</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ user.last_login_at }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-danger': user.is_active == 0, 'btn-primary': user.is_active == 1}">{{ user.is_active == 1 ? 'Active' : 'Inactive' }}</button>
                                            <button type="button" :class="{'btn': true, 'btn-sm': true, 'btn-danger': user.is_active == 0, 'btn-primary': user.is_active == 1, 'split-bg-danger': user.is_active == 0, 'split-bg-primary': user.is_active == 1}" class="dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">	<span class="visually-hidden">Toggle Dropdown</span></button>
                                            <ul class="dropdown-menu">
                                                <li v-if="user.is_active != 1"><Link class="dropdown-item" :href="`/admin/users/${user.id}/change-user-status?is_active=1`">Active</Link></li>
                                                <li v-if="user.is_active != 0"><Link class="dropdown-item" :href="`/admin/users/${user.id}/change-user-status?is_active=0`">Inactive</Link></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
											<button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="true">Action</button>
											<ul class="dropdown-menu" data-popper-placement="top-start" data-popper-reference-hidden="" data-popper-escaped="">
												<li><Link class="dropdown-item" :href="`/admin/users/${user.id}`">Detail</Link></li>
                                                <li><Link class="dropdown-item" :href="`/admin/users/${user.id}/edit`">Edit</Link></li>
                                                <li><a class="dropdown-item" href="#" @click.prevent="destroy(user.id)">Hapus</a></li>
											</ul>
										</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" colspan="8" v-if="!users.data.length">Data Tidak Tersedia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <Pagination :links="users.links" align="end" />

                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
</template>

<script>
    //import layout admin
    import LayoutAdmin from '../../../../Layouts/Layout.vue';

    //import component pagination
    import Pagination from '../../../../Components/Pagination.vue';

    // import Link
    import { Link } from '@inertiajs/inertia-vue3';

    //import sweet alert2
    import Swal from 'sweetalert2';

    //import ref from vue
    import {
        ref
    } from 'vue';

    //import reactive
    import { reactive } from 'vue';

    // import Head from Inertia
    import {
        Head
    } from '@inertiajs/inertia-vue3';

    import { Inertia } from '@inertiajs/inertia';

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
            users: Object
        },

        // initialization composition API
        setup() {
            // define state search
            const form = reactive({
                search: ref("" || (new URL(document.location)).searchParams.get('search')),
                level: ref((new URL(document.location)).searchParams.get('level') || ''),
                status_active: ref((new URL(document.location)).searchParams.get('status_active') || ''),
                member_type: ref((new URL(document.location)).searchParams.get('member_type') || ''),
            });

            // define method search
            const handleSearch = () => {
                Inertia.get('/admin/users', {
                    //send params "search" with value from state "search"
                    search: form.search,
                    level: form.level,
                    status_active: form.status_active,
                    member_type: form.member_type,
                })
            }

            const destroy = (id) => {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus'
                })
                .then((result) => {
                    if (result.isConfirmed) {

                        Inertia.delete(`/admin/users/${id}`);

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'User Berhasil Dihapus!.',
                            icon: 'success',
                            timer: 1000,
                            showConfirmButton: false,
                        });
                    }
                })
            }

            return {
                form,
                handleSearch,
                destroy
            }
        }
    }
</script>
