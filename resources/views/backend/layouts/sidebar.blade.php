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

    <!-- role -->

    <li>
        <a href="javascript:;.html" class="side-menu side-menu{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='role_list'||$active_menu=='role_add'||$active_menu=='kiot'|| $active_menu=='setting_list'|| $active_menu=='log_list'||$active_menu=='banner_add'|| $active_menu=='banner_list')?'--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="zap"></i> </div>
              <div class="side-menu__title">
                  Quyền người dùng
                  <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
              </div>
        </a>
        <ul class="{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='role_list'||$active_menu=='role_add'||$active_menu=='kiot'|| $active_menu=='setting_list'|| $active_menu=='banner_add'|| $active_menu=='banner_list')?'side-menu__sub-open':''}}">
             
              <li>
                  <a href="{{route('admin.roleuser.index')}}" class="side-menu {{$active_menu=='role_list'||$active_menu=='roles'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="octagon"></i> </div>
                      <div class="side-menu__title"> Danh sách quyền</div>
                  </a>
              </li>
              <li>
                  <a href="{{route('admin.roleuser.create')}}" class="side-menu {{$active_menu=='role_list'?'side-menu--active':''}}">
                      <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                      <div class="side-menu__title"> Thêm quyền người dùng</div>
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
<li>
    <a href="javascript:;" class="side-menu side-menu{{ ($active_menu=='music_management') ? '--active' : '' }}">
        <div class="side-menu__icon"> <i data-lucide="music"></i> </div>
        <div class="side-menu__title">
            Quản lý Âm Nhạc
            <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{ ($active_menu=='music_management') ? 'side-menu__sub-open' : '' }}">
    <!-- Music Company -->
    <li>
        <a href="javascript:;" class="side-menu {{ $active_menu=='musiccompany_management' ? 'side-menu--active' : '' }}" onclick="toggleMusicCompany()">
            <div class="side-menu__icon"> <i data-lucide="building"></i> </div>
            <div class="side-menu__title">Công ty Âm nhạc</div>
            <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
        </a>
        <ul id="musicCompanyList" class="{{ ($active_menu=='musiccompany_management') ? 'side-menu__sub-open' : '' }}" style="display: none;">
            <li>
                <a href="{{ route('admin.musiccompany.index') }}" class="side-menu {{ $active_menu=='musiccompany_list' ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                    <div class="side-menu__title">Danh sách Công ty Âm nhạc</div>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.musiccompany.create') }}" class="side-menu {{ $active_menu=='musiccompany_add' ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                    <div class="side-menu__title">Thêm Công ty Âm nhạc</div>
                </a>
            </li>
        </ul>
    </li>
         <!-- Thêm folder cho  Bài hát -->
<li>
    <a href="javascript:;" class="side-menu {{ $active_menu=='song_management' ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon"> <i data-lucide="headphones"></i> </div> <!-- Đổi thành biểu tượng nhạc -->
        <div class="side-menu__title">Bài hát</div>
        <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
    </a>
    <ul class="{{ ($active_menu=='song_management') ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="" class="side-menu {{ $active_menu=='song_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách Bài hát</div>
            </a>
        </li>
        <li>
            <a href="" class="side-menu {{ $active_menu=='song_add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm Bài hát</div>
            </a>
        </li>
    </ul>
</li>
<!-- Thêm folder cho quản lý Playlist -->
<li>
    <a href="javascript:;" class="side-menu {{ $active_menu=='playlist_management' ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon"> <i data-lucide="album"></i>  </div> <!-- Biểu tượng cho Playlist -->
        <div class="side-menu__title">Playlist</div>
        <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
    </a>
    <ul class="{{ ($active_menu=='playlist_management') ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="" class="side-menu {{ $active_menu=='playlist_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách Playlist</div>
            </a>
        </li>
        <li>
            <a href="" class="side-menu {{ $active_menu=='playlist_add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm Playlist</div>
            </a>
        </li>
    </ul>
</li>


        <!-- Các mục khác cho Bài hát có thể được thêm vào đây -->
        
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

    <!-- start group -->
    <li>
        <a href="javascript:;" class="side-menu side-menu{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='group_list'||$active_menu=='group_add'||$active_menu=='kiot'|| $active_menu=='grouptype_list'|| $active_menu=='grouptype_add'||$active_menu=='banner_add'|| $active_menu=='banner_list')?'--active':''}}">
            <div class="side-menu__icon"> <i data-lucide="box"></i> </div>
                <div class="side-menu__title">
                    Groups 
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
        </a>
        <ul class="{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='group_list'||$active_menu=='group_add'||$active_menu=='kiot'|| $active_menu=='gtype_list'|| $active_menu=='gtype_add'|| $active_menu=='banner_list')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.group.index')}}" class="side-menu {{$active_menu=='groups'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                    <div class="side-menu__title">Nhóm </div>
                </a>
            </li>
                <li>
                    <a href="{{route('admin.grouptype.index')}}" class="side-menu {{$active_menu=='gtype_add'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                        <div class="side-menu__title"> Loại nhóm </div>
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
     <!-- end group -->


        
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
                    <a href="{{route('admin.grouptype.index')}}" class="side-menu {{$active_menu=='gtype_add'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                        <div class="side-menu__title"> Loại nhóm </div>
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