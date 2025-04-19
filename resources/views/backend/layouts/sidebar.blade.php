<nav class="side-nav">
   
<ul>
        <li>
            <a href="{{route('admin.home')}}" class="side-menu side-menu{{$active_menu=='dashboard'?'--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="side-menu__title"> Dashboard </div>
            </a>
        </li> 
       
    <!-- Blog -->
    <li>
        <a href="javascript:;.html" class="side-menu side-menu{{( $active_menu=='blog_list'|| $active_menu=='blog_add'||$active_menu=='blogcat_list'|| $active_menu=='blogcat_add' )?'--active':''}}">
            <div class="side-menu__icon"> <i data-lucide="align-center"></i> </div>
            <div class="side-menu__title">
                Bài viết
                <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{ ($active_menu=='blog_list'|| $active_menu=='blog_add'||$active_menu=='blogcat_list'|| $active_menu=='blogcat_add')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.blog.index')}}" class="side-menu {{$active_menu=='blog_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="compass"></i> </div>
                    <div class="side-menu__title">Danh sách bài viết </div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.blog.create')}}" class="side-menu {{$active_menu=='blog_add'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                    <div class="side-menu__title"> Thêm bài viết</div>
                </a>
            </li>
            
            <li>
                <a href="{{route('admin.blogcategory.index')}}" class="side-menu {{$active_menu=='blogcat_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="hash"></i> </div>
                    <div class="side-menu__title">Danh mục bài viết </div>
                </a>
            </li>
      </ul>
  </li>
     
    <li>
        <a href="javascript:;" class="side-menu  class="side-menu {{($active_menu =='ugroup_add'|| $active_menu=='ugroup_list' || $active_menu =='ctm_add'|| $active_menu=='ctm_list'  )?'side-menu--active':''}}">
            <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
            <div class="side-menu__title">
                Người dùng 
                <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{($active_menu =='ugroup_add'|| $active_menu=='ugroup_list' || $active_menu =='ctm_add'|| $active_menu=='ctm_list')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.user.index')}}" class="side-menu {{$active_menu=='ctm_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                    <div class="side-menu__title">Danh sách người dùng</div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.user.create')}}" class="side-menu {{$active_menu=='ctm_add'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                    <div class="side-menu__title"> Thêm người dùng</div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.ugroup.index')}}" class="side-menu {{$active_menu=='ugroup_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="circle"></i> </div>
                    <div class="side-menu__title">Ds nhóm người dùng</div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.ugroup.create')}}" class="side-menu {{$active_menu=='ugroup_add'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                    <div class="side-menu__title"> Thêm nhóm người dùng</div>
                </a>
            </li>
        </ul>
    </li>

   
    <!-- Comments -->
    <li>
    <a href="javascript:;.html" class="side-menu side-menu {{($active_menu =='comment_add'|| $active_menu=='comment_list') ? 'side-menu--active' : ''}}">
        <div class="side-menu__icon"> <i data-lucide="message-square"></i> </div>
        <div class="side-menu__title">
            Bình luận   
            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{($active_menu =='comment_add'|| $active_menu=='comment_list') ? 'side-menu__sub-open' : ''}}">
        <li>
            <a href="{{route('admin.comments.index')}}" class="side-menu {{$active_menu=='comment_list' ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách bình luận</div>
            </a>
        </li>
        
    </ul>
</li>
<!-- Quản lý Bài hát -->
<!-- Quản lý Âm nhạc -->
<li>
    <a href="javascript:;" class="side-menu {{ in_array($active_menu, ['listener_management','playlist_management','music_management', 'musiccompany_management', 'singer_management', 'musictype_management', 'composer_management', 'song_management']) ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon"> <i data-lucide="music"></i> </div>
        <div class="side-menu__title">
            Quản lý âm nhạc
            <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{ in_array($active_menu, ['listener_management','playlist_management','music_management', 'musiccompany_management', 'singer_management', 'musictype_management', 'composer_management', 'song_management']) ? 'side-menu__sub-open' : '' }}">
        <!-- Công ty Âm nhạc -->
        <li>
            <a href="javascript:;" class="side-menu {{ $active_menu == 'musiccompany_management' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="building"></i> </div>
                <div class="side-menu__title">Công ty âm nhạc</div>
                <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
            </a>
            <ul class="{{ $active_menu == 'musiccompany_management' ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.musiccompany.index') }}" class="side-menu {{ $active_menu == 'musiccompany_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title">Danh sách công ty âm nhạc</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.musiccompany.create') }}" class="side-menu {{ $active_menu == 'musiccompany_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title">Thêm công ty âm nhạc</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Ca Sĩ -->
        <li>
            <a href="javascript:;" class="side-menu {{ $active_menu == 'singer_management' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
                <div class="side-menu__title">Ca sĩ</div>
                <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
            </a>
            <ul class="{{ $active_menu == 'singer_management' ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.singer.index') }}" class="side-menu {{ $active_menu == 'singer_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title">Danh sách ca sĩ</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.singer.create') }}" class="side-menu {{ $active_menu == 'singer_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title">Thêm ca sĩ</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Loại Nhạc -->
        <li>
            <a href="javascript:;" class="side-menu {{ $active_menu == 'musictype_management' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="music"></i> </div>
                <div class="side-menu__title">Loại nhạc</div>
                <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
            </a>
            <ul class="{{ $active_menu == 'musictype_management' ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.musictype.index') }}" class="side-menu {{ $active_menu == 'musictype_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title">Danh sách loại nhạc</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.musictype.create') }}" class="side-menu {{ $active_menu == 'musictype_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title">Thêm loại nhạc</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Nhà Soạn Nhạc -->
        <li>
            <a href="javascript:;" class="side-menu {{ $active_menu == 'composer_management' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="Mic"></i> </div>
                <div class="side-menu__title">Nhạc sĩ</div>
                <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
            </a>
            <ul class="{{ $active_menu == 'composer_management' ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.composer.index') }}" class="side-menu {{ $active_menu == 'composer_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title">Danh sách nhạc sĩ sáng tác</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.composer.create') }}" class="side-menu {{ $active_menu == 'composer_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title">Thêm nhạc sĩ</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Bài Hát -->
        <li>
            <a href="javascript:;" class="side-menu {{ $active_menu == 'song_management' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="Headphones"></i>
                </div>
                <div class="side-menu__title">Bài hát</div>
                <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
            </a>
            <ul class="{{ $active_menu == 'song_management' ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.song.index') }}" class="side-menu {{ $active_menu == 'song_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title">Danh sách bài hát</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.song.create') }}" class="side-menu {{ $active_menu == 'song_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title">Thêm bài hát</div>
                    </a>
                </li>
            </ul>
        </li>
       <!-- Playlist -->
<li>
    <a href="javascript:;" class="side-menu {{ $active_menu == 'playlist_management' ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
        <div class="side-menu__title">Playlist</div>
        <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
    </a>
    <ul class="{{ $active_menu == 'playlist_management' ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('admin.playlist.index') }}" class="side-menu {{ $active_menu == 'playlist_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách Playlist</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.playlist.create') }}" class="side-menu {{ $active_menu == 'playlist_add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm Playlist</div>
            </a>
        </li>
    </ul>
</li>

<!-- topic -->
<li>
    <a href="javascript:;" class="side-menu {{ $active_menu == 'topic_manage' ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon"> <i data-lucide="slack"></i> </div>
        <div class="side-menu__title">Chủ đề</div>
        <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
    </a>
    <ul class="{{ $active_menu == 'topic_manage' ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('admin.topic.index') }}" class="side-menu {{ $active_menu == 'topic_manage' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách chủ đề</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.topic.create') }}" class="side-menu {{ $active_menu == 'topic_create' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm chủ đề</div>
            </a>
        </li>
    </ul>
</li>

<!-- Listener -->
<li>
    <a href="javascript:;" class="side-menu {{ $active_menu == 'listener_management' ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
        <div class="side-menu__title">Listener</div>
        <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
    </a>
    <ul class="{{ $active_menu == 'listener_management' ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('admin.listener.index') }}" class="side-menu {{ $active_menu == 'listener_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách Listener</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.listener.create') }}" class="side-menu {{ $active_menu == 'listener_add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm Listener</div>
            </a>
        </li>
    </ul>
</li>
    </ul>
</li>



 <!-- Resource  -->
 <li>
            <a href="javascript:;" class="side-menu {{($active_menu=='resource_list'|| $active_menu=='resource_add'|| $active_menu=='resourcetype_list'|| $active_menu=='resourcelinktype_list')?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="file"></i> </div>
                <div class="side-menu__title">
                    Tài nguyên
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{($active_menu=='resource_list'|| $active_menu=='resource_add'|| $active_menu=='resourcetype_list'|| $active_menu=='resourcelinktype_list')?'side-menu__sub-open':''}}">
                <li>
                    <a href="{{route('admin.resources.index')}}" class="side-menu {{$active_menu=='resource_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                        <div class="side-menu__title">Danh sách tài nguyên</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.resources.create')}}" class="side-menu {{$active_menu=='resource_add'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title"> Thêm tài nguyên</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.resource-types.index')}}" class="side-menu {{$active_menu=='resourcetype_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="folder"></i> </div>
                        <div class="side-menu__title"> Loại tài nguyên </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.resource-link-types.index')}}" class="side-menu {{$active_menu=='resourcelinktype_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="link"></i> </div>
                        <div class="side-menu__title"> Loại liên kết tài nguyên </div>
                    </a>
                </li>
            </ul>
        </li>

   <!-- group -->
<li>
    <a href="javascript:;" class="side-menu side-menu{{($active_menu=='group_list'||$active_menu=='group_add'||$active_menu=='groupmember'||$active_menu=='grouptype'||$active_menu=='grouprole')?'--active':''}}">
        <div class="side-menu__icon"> <i data-lucide="align-center"></i> </div>
        <div class="side-menu__title">
            Nhóm
            <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{($active_menu=='group_list'||$active_menu=='group_add'||$active_menu=='groupmember'||$active_menu=='grouptype'||$active_menu=='grouprole')?'side-menu__sub-open':''}}">
        <!-- Danh sách nhóm -->
        <li>
            <a href="{{route('admin.group.index')}}" class="side-menu {{$active_menu=='group_list'?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="compass"></i> </div>
                <div class="side-menu__title">Danh sách nhóm</div>
            </a>
        </li>
        <!-- Thêm nhóm -->
        <li>
            <a href="{{route('admin.group.create')}}" class="side-menu {{$active_menu=='group_add'?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm nhóm</div>
            </a>
        </li>
        <!-- Thành viên nhóm -->
        <li>
            <a href="{{route('admin.groupmember.index')}}" class="side-menu {{$active_menu=='groupmember'?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
                <div class="side-menu__title">Thành viên nhóm</div>
            </a>
        </li>
        <!-- Loại nhóm -->
        <li>
            <a href="{{route('admin.grouptype.index')}}" class="side-menu {{$active_menu=='grouptype'?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                <div class="side-menu__title">Loại nhóm</div>
            </a>
        </li>
        <!-- Vai trò nhóm -->
        <li>
            <a href="{{route('admin.grouprole.index')}}" class="side-menu {{$active_menu=='grouprole'?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="briefcase"></i> </div>
                <div class="side-menu__title">Vai trò nhóm</div>
            </a>
        </li>
    </ul>
</li>


       
 <!-- Tag -->
 <li>
            <a href="javascript:;" class="side-menu {{($active_menu=='tag_list'|| $active_menu=='tag_add')?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="anchor"></i> </div>
                <div class="side-menu__title">
                    Tag
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{($active_menu=='tag_list'|| $active_menu=='tag_add')?'side-menu__sub-open':''}}">
                <li>
                    <a href="{{ route('admin.tag.index') }}" class="side-menu {{$active_menu=='tag_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="anchor"></i> </div>
                        <div class="side-menu__title">Danh sách Tag </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tag.create') }}" class="side-menu {{$active_menu=='tag_add'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title"> Thêm Tag</div>
                    </a>
                </li>
            </ul>
        </li>
        
  <!-- start interact -->

  <li>
        <a href="javascript:;" class="side-menu side-menu{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='interact_list'||$active_menu=='interact_add'||$active_menu=='kiot'|| $active_menu=='interactype_list'|| $active_menu=='interactype_add'||$active_menu=='banner_add'|| $active_menu=='banner_list')?'--active':''}}">
            <div class="side-menu__icon"> <i data-lucide="book"></i> </div>
                <div class="side-menu__title">
                    Tương tác 
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
        </a>
        <ul class="{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='interact_list'||$active_menu=='interact_add'||$active_menu=='kiot')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.userpage.index')}}" class="side-menu {{$active_menu=='interact_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="user-check"></i> </div>
                    <div class="side-menu__title">Cá nhân </div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.motion.index')}}" class="side-menu {{$active_menu=='motion_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                    <div class="side-menu__title">Danh sách motion</div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.motion.create')}}" class="side-menu {{$active_menu=='motion_add'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                    <div class="side-menu__title">Thêm motion</div>
                </a>
            </li>
                <!-- <li>
                    <a href="" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Top Menu </div>
                    </a>
                </li> -->
            </ul>
        </li>

    <!-- start fanclub -->

    <li>
        <a href="javascript:;" class="side-menu {{ in_array($active_menu, ['fanclub_list','fanclub_add','fanclub_edit', 'fanclub_delete']) ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-lucide="codesandbox"></i> </div>
            <div class="side-menu__title">
                Quản lí câu lạc bộ
                <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{ in_array($active_menu, ['fanclub_list','fanclub_add','fanclub_edit', 'fanclub_delete']) ? 'side-menu__sub-open' : '' }}">
            <!-- Câu lạc bộ -->
            <li>
                <a href="javascript:;" class="side-menu {{ $active_menu == 'fanclub_list' ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-lucide="package
"></i> </div>
                    <div class="side-menu__title">Câu lạc bộ</div>
                    <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
                </a>
                <ul class="{{ $active_menu == 'fanclub_list' ? 'side-menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('admin.Fanclub.index') }}" class="side-menu {{ $active_menu == 'musiccompany_list' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                            <div class="side-menu__title">Danh sách câu lạc bộ</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.Fanclub.create') }}" class="side-menu {{ $active_menu == 'fanclub_add' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                            <div class="side-menu__title">Thêm câu lạc bộ</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!--Tài nguyên -->
            <li>
                <a href="javascript:;" class="side-menu {{ $active_menu == 'fanclub_list' ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-lucide="shopping-bag
"></i> </div>
                    <div class="side-menu__title">Tài nguyên</div>
                    <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
                </a>
                <ul class="{{ $active_menu == 'fanclub_list' ? 'side-menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('admin.FanclubItem.index') }}" class="side-menu {{ $active_menu == 'musiccompany_list' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                            <div class="side-menu__title">Danh sách tài nguyên </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.FanclubItem.create') }}" class="side-menu {{ $active_menu == 'fanclub_add' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                            <div class="side-menu__title">Thêm tài nguyên</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Thành viên câu lạc bộ -->
            <li>
                <a href="javascript:;" class="side-menu {{ $active_menu == 'fanclub_list' ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-lucide="building"></i> </div>
                    <div class="side-menu__title">Thành viên câu lạc bộ</div>
                    <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
                </a>
                <ul class="{{ $active_menu == 'fanclub_list' ? 'side-menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('admin.FanclubUser.index') }}" class="side-menu {{ $active_menu == 'fanclub_list' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                            <div class="side-menu__title">Danh sách thành viên</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.FanclubUser.create') }}" class="side-menu {{ $active_menu == 'fanclub_add' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                            <div class="side-menu__title">Thêm thành viên</div>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </li>
    <!-- end fanclub -->

    <!-- start event -->

    <li>
        <a href="javascript:;" class="side-menu {{ in_array($active_menu, ['event_list','event_add','event_edit', 'event_delete', 'eventtype_list','eventtype_add','eventtype_edit', 'eventtype_delete','event_group_add', 'event_group_list','event_group_edit', 'event_group_delete','event_user_list','event_user_add','event_user_edit', 'event_user_delete']) ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-lucide="globe"></i> </div>
            <div class="side-menu__title">
                Quản lí sự kiện
                <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{ in_array($active_menu, ['event_list','event_add','event_edit', 'event_delete', 'eventtype_list','eventtype_add','eventtype_edit', 'eventtype_delete','event_group_add', 'event_group_list','event_group_edit', 'event_group_delete','event_user_list','event_user_add','event_user_edit', 'event_user_delete']) ? 'side-menu__sub-open' : '' }}">
            <!-- Sự kiện -->
            <li>
                <a href="javascript:;" class="side-menu {{ $active_menu == 'event_list' ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-lucide="briefcase"></i> </div>
                    <div class="side-menu__title">Sự kiện</div>
                    <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
                </a>
                <ul class="{{ $active_menu == 'event_list' ? 'side-menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('admin.Event.index') }}" class="side-menu {{ $active_menu == 'event_list' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                            <div class="side-menu__title">Danh sách sự kiện</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.Event.create') }}" class="side-menu {{ $active_menu == 'event_add' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                            <div class="side-menu__title">Thêm sự kiện</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Loại sự kiện -->
            <li>
                <a href="javascript:;" class="side-menu {{ $active_menu == 'eventtype_list' ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-lucide="code"></i> </div>
                    <div class="side-menu__title">Loại hình sự kiện</div>
                    <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
                </a>
                <ul class="{{ $active_menu == 'eventtype_list' ? 'side-menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('admin.EventType.index') }}" class="side-menu {{ $active_menu == 'eventtype_list' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                            <div class="side-menu__title">Danh sách loại sự kiện</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.EventType.create') }}" class="side-menu {{ $active_menu == 'eventtype_add' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                            <div class="side-menu__title">Thêm loại sự kiện</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- người tham gia -->
            <li>
                <a href="javascript:;" class="side-menu {{ $active_menu == 'event_user_list' ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
                    <div class="side-menu__title">Người tham gia</div>
                    <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
                </a>
                <ul class="{{ $active_menu == 'event_user_list' ? 'side-menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('admin.EventUser.index') }}" class="side-menu {{ $active_menu == 'event_user_list' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                            <div class="side-menu__title">Danh sách người tham gia</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.EventUser.create') }}" class="side-menu {{ $active_menu == 'event_user_add' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                            <div class="side-menu__title">Thêm người tham gia sự kiện</div>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Nhóm tham gia sự kiện -->  
            <li>
                <a href="javascript:;" class="side-menu {{ $active_menu == 'event_group_list' ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                    <div class="side-menu__title">Nhóm tham gia</div>
                    <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
                </a>
                <ul class="{{ $active_menu == 'event_group_list' ? 'side-menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('admin.EventGroup.index') }}" class="side-menu {{ $active_menu == 'event_group_list' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                            <div class="side-menu__title">Danh sách nhóm tham gia</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.EventGroup.create') }}" class="side-menu {{ $active_menu == 'event_group_add' ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                            <div class="side-menu__title">Thêm nhóm tham gia sự kiện</div>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <!-- end event -->
    
     
     <!-- setting menu -->
     <li>
        <a href="javascript:;.html" class="side-menu side-menu{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='role_list'||$active_menu=='role_add'||$active_menu=='kiot'|| $active_menu=='setting_list'|| $active_menu=='log_list'||$active_menu=='banner_add'|| $active_menu=='banner_list')?'--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="settings"></i> </div>
              <div class="side-menu__title">
                  Cài đặt
                  <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
              </div>
        </a>
        <ul class="{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='role_list1'||$active_menu=='role_add'||$active_menu=='kiot'|| $active_menu=='setting_list'|| $active_menu=='banner_add'|| $active_menu=='banner_list')?'side-menu__sub-open':''}}">
             
              <li>
                  <a href="{{route('admin.role.index',1)}}" class="side-menu {{$active_menu=='role_list2'||$active_menu=='role_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="octagon"></i> </div>
                      <div class="side-menu__title"> Roles</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('admin.cmdfunction.index',1)}}" class="side-menu {{$active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="moon"></i> </div>
                      <div class="side-menu__title"> Chức năng</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('admin.setting.edit',1)}}" class="side-menu {{$active_menu=='setting_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="key"></i> </div>
                      <div class="side-menu__title"> Thông tin công ty</div>
                  </a>
              </li>
              
              
          </ul>
    </li>
    
</ul>
</nav>