<style>
    .search{
       opacity: 0;
    }
  .profile-container {
    font-family: Arial, sans-serif;
    background: #f4f6fb;
    max-width: 100%;
    margin: 0px 10px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px #ccc;
  }
  .cover-photo {
    background: linear-gradient(120deg, #d4145a 0%, #b3125c 100%);
    height: 180px;
    width: 100%;
    background-size: cover;
    background-position: center;
  }
  .profile-header {
    display: flex;
    align-items: center;
    padding: 0 30px;
    margin-top: -60px;
    z-index: 99999999 ;
    position: relative;
  
  }
  .avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 6px solid #fff;
    background: #fff;
    margin-right: 30px;
  }
  .profile-info {
    flex: 1;
  }
  .profile-info h2 {
    margin: 0;
    font-size: 2em;
  }
  .job-title {
    color: #888;
    margin-bottom: 10px;
  }
  .profile-stats {
    display: flex;
    gap: 30px;
    margin-bottom: 10px;
  }
  .profile-stats strong{
      color: black;
  }
  .profile-stats div {
    text-align: center;
  }
  .profile-stats span {
    display: block;
    color: #888;
    font-size: 0.9em;
  }
  .profile-actions {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .social-icons span {
    background: #eee;
    border-radius: 50%;
    padding: 5px 8px;
    margin-right: 5px;
    font-size: 0.9em;
  }
  .profile-tabs {
    display: flex;
    border-bottom: 1px solid #ddd;
    background: #fff;
    padding-left: 30px;
  }
  .profile-tabs a {
    padding: 15px 25px;
    color: #1877f2;
    text-decoration: none;
    font-weight: 500;
    border-bottom: 2px solid transparent;
    transition: 0.2s;
  }
  .profile-tabs a.active, .profile-tabs a:hover {
    border-bottom: 2px solid #1877f2;
    background: #f4f6fb;
  }
  .profile-content {
    display: flex;
    padding: 30px;
    background: #fff;
  }
  .profile-sidebar {
    width: 270px;
    margin-right: 30px;
  }
  .profile-sidebar p{
      color: black;
  }
  .profile-sidebar h3 {
    margin-top: 0;
    color:#555;
  }
  .profile-sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0 0 20px 0;
  }
  .profile-sidebar ul li {
    margin-bottom: 8px;
    color: #555;
  }
  .photos {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 12px 0 0 0;
  }
  .photos img {
    width: 75px;
    height: 75px;
    border-radius: 8px;
    margin-right: 5px;
    margin-bottom: 5px;
    object-fit: cover;
    border: 1px solid #e0e6ed;
  }
  .profile-main {
    flex: 1;
  }
  .status-box {
    background: #f4f6fb;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
  }
  .status-box textarea {
    width: 98%;
    border: none;
    border-radius: 5px;
    padding: 10px;
    resize: none;
    min-height: 50px;
    margin-bottom: 10px;
    font-size: 1em;
  }
  .status-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
  }
  .status-actions button {
    background: #eee;
    border: none;
    padding: 7px 15px;
    border-radius: 5px;
    cursor: pointer;
  }
  .status-actions .post-btn {
    background: linear-gradient(90deg, #4f8cff, #38b6ff);
    color: #fff;
  }
  .post {
    background: #f4f6fb;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
  }
  .post strong{
      color: black;
  } 
  .post-header {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .avatar-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
  }
  .post-header strong {
    font-size: 1em;
  }
  .post-header span {
    color: #888;
    font-size: 0.9em;
    margin-left: 5px;
  }
  .post-content {
    margin-top: 10px;
    font-size: 1em;
    color: #555;
  }
  .profile-header-modern {
    display: flex;
    align-items: flex-end;
    /* background: linear-gradient(120deg, #d4145a 0%, #b3125c 100%); */
    /* background: rgb(247, 136, 151); */
    background: url('./');
    height: 400px;
    /* border-top-left-radius: 16px;
    border-top-right-radius: 16px; */
    position: relative;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    padding: 0 0 0 60px;
  }
  .profile-header-modern .avatar-modern {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    border: 6px solid #fff;
    background: #eee;
    object-fit: cover;
    position: absolute;
    left: 40px;
    bottom: -70px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.10);
  }
  .profile-header-modern .profile-info-modern {
    margin-left: 180px;
    margin-bottom: 40px;
  }
  .profile-header-modern .profile-info-modern h2 {
    color: #fff;
    font-size: 2.8em;
    font-weight: bold;
    margin: 0 0 8px 0;
    letter-spacing: 1px;
  }
  .profile-header-modern .profile-info-modern .job-title {
    color: #f3e6ef;
    font-size: 1.2em;
    margin-bottom: 18px;
  }
  .profile-header-modern .profile-stats-modern {
    display: flex;
    gap: 40px;
    margin-top: 10px;
  }
  .profile-header-modern .profile-stats-modern div {
    text-align: center;
  }
  .profile-header-modern .profile-stats-modern strong {
    color: #fff;
    font-size: 1.3em;
    font-weight: bold;
  }
  .profile-header-modern .profile-stats-modern span {
    display: block;
    color: #f3e6ef;
    font-size: 1em;
    margin-top: 2px;
  }
  .profile-header-modern-bg {
    background: #f6f8fc;
    padding-top: 90px;
    border-bottom-left-radius: 16px;
    border-bottom-right-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    margin-bottom: 30px;
  }
  .post-actions{
    color:#555;
  }
  .comments b{
    color: black;
  }
  .comments div{
    color: #555;
  }
  .show-more-comments {
    display: block;
    margin: 0 auto 5px auto;
    padding: 7px 18px;
    background: #f4f6fb;
    color: #1877f2;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1em;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
  }
  .show-more-comments:hover {
    background: #e6eaff;
    color: #1250b0;
  }
  
  @media (max-width: 700px) {
    .profile-header-modern {
      flex-direction: column;
      align-items: center;
      padding: 0;
      height: 320px;
    }
    .profile-header-modern .avatar-modern {
      position: static;
      margin-top: -70px;
      left: unset;
      bottom: unset;
    }
    .profile-header-modern .profile-info-modern {
      margin-left: 0;
      margin-bottom: 20px;
      text-align: center;
    }
  }
  .comment{
    padding: 10px 0px;
  }
  .modern-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 12px 0 0 0;
  }
  .modern-gallery-item {
    position: relative;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 8px #0001;
    background: #fff;
    transition: box-shadow 0.2s, transform 0.2s;
    cursor: pointer;
    width: 300px;
    height: 300px;
    margin: 0;
    min-width: 0;
    min-height: 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .modern-gallery-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: filter 0.2s;
  }
  .profile-search-bar {
    background: #fff;
    padding: 18px 40px 18px 60px;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    justify-content: flex-end;
  }
  #profile-search-form input[type="text"] {
    padding: 10px 16px;
    border-radius: 8px;
    border: 1.5px solid #e0e6ed;
    font-size: 1.08em;
    background: #f8fafc;
    width: 320px;
    transition: border 0.2s;
  }
  #profile-search-form input[type="text"]:focus {
    border: 1.5px solid #1877f2;
    outline: none;
  }
  #profile-search-form button {
    background: linear-gradient(90deg, #4f8cff, #38b6ff);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 22px;
    font-size: 1.08em;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  #profile-search-form button:hover {
    background: #1250b0;
  }
  .follow-btn {
    background: #1877f2;
    color: #fff;
    border: none;
    border-radius: 22px;
    padding: 10px 10px;
    font-size: 16px !important ;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 2px 8px #0001;
    transition: background 0.2s, box-shadow 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    height: 40px;
    margin-left:20px;
    margin-top:10px;
  
  }
  .follow-btn:hover {
    background: #1250b0;
    box-shadow: 0 4px 16px #0002;
  }
 
      .followers-list-container {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 16px #0001;
        padding: 32px 28px 24px 28px;
        max-width: 90%;
        margin: 40px auto 0 auto;
        margin-bottom:40px !important;
      }
      .followers-title {
        font-size: 1.6em;
        font-weight: bold;
        color: #222;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
      }
      .followers-search-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 18px;
      }
      #followers-search-input {
        flex: 1;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1.5px solid #e0e6ed;
        font-size: 1.08em;
        background: #f8fafc;
        transition: border 0.2s;
      }
      #followers-search-input-changer {
        flex: 1;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1.5px solid #e0e6ed;
        font-size: 1.08em;
      }
      #followers-search-input-changer:focus {
        border: 1.5px solid #1877f2;
        outline: none;
      }
      #followers-search-input:focus {
        border: 1.5px solid #1877f2;
        outline: none;
      }
      #followers-search-btn {
        background:  linear-gradient(90deg, #4f8cff, #38b6ff);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 22px;
        font-size: 1.08em;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
      }
      #followers-search-btn:hover {
        background: #1250b0;
      }
      .followers-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
      }
      .follower-card {
        display: flex;
        align-items: center;
        background: #f4f6fb;
        border-radius: 10px;
        padding: 14px 18px;
        box-shadow: 0 1px 6px #0001;
        transition: box-shadow 0.2s, transform 0.2s;
        gap: 18px;
      }
      .follower-card-check {
        display: flex;
        align-items: center;
        background: #f4f6fb;
        border-radius: 10px;
        padding: 14px 18px;
        box-shadow: 0 1px 6px #0001;
        transition: box-shadow 0.2s, transform 0.2s;
        gap: 18px;
      }
      .follower-card-check:hover {
        box-shadow: 0 4px 18px #0002;
        transform: translateY(-2px) scale(1.01);
      } 
      .follower-card:hover {
        box-shadow: 0 4px 18px #0002;
        transform: translateY(-2px) scale(1.01);
      }
      .follower-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px #0001;
      }
      .follower-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
      }
      .follower-name {
        font-weight: 600;
        font-size: 1.15em;
        color: #222;
      }
      .follower-email {
        color: #888;
        font-size: 0.98em;
        display: flex;
        align-items: center;
        gap: 6px;
      }
      .follower-view-btn {
        background: linear-gradient(90deg, #4f8cff, #38b6ff);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 1em;
        font-weight: 500;
        text-decoration: none;
        transition: background 0.2s;
        margin-left: 10px;
      }
      .follower-view-btn:hover {
        background: #1250b0;
      }
      .no-followers {
        color: #888;
        text-align: center;
        padding: 24px 0;
        font-size: 1.1em;
      }
     
      .modern-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px 0 0 0;
      }
      .modern-gallery-item {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 8px #0001;
        background: #fff;
        transition: box-shadow 0.2s, transform 0.2s;
        cursor: pointer;
        width: 250px;
        height: 250px;
        margin: 0;
        min-width: 0;
        min-height: 0;
        display: flex;
        align-items: center;
        margin:0px 5px;
        justify-content: center;
      }
      .modern-gallery-item:hover {
        box-shadow: 0 8px 32px #0002, 0 2px 12px #0003;
        transform: translateY(-4px) scale(1.03);
      }
      .modern-gallery-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: filter 0.2s;
      }
      .modern-gallery-item:hover .modern-gallery-img {
        filter: brightness(0.92) blur(1px);
      }
      .modern-gallery-delete {
        position: absolute;
        top: 10px; right: 10px;
        background: none;
        color: #555;
        border: none;
        border-radius: 50%;
        width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2em;
        opacity: 0;
        transition: opacity 0.2s;
        z-index: 2;
        cursor: pointer;
      }
      .modern-gallery-item:hover .modern-gallery-delete {
        opacity: 1;
      }
      .modern-lightbox {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0; top: 0; width: 100vw; height: 100vh;
        background: rgba(24,13,38,0.88);
        align-items: center; justify-content: center;
      }
      .modern-lightbox img {
        max-width: 92vw;
        max-height: 82vh;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.25);
      }
      .modern-lightbox-close {
        position: absolute;
        top: 32px; right: 48px;
        font-size: 44px;
        color: #fff;
        cursor: pointer;
        z-index: 10001;
        text-shadow: 0 2px 8px #000a;
      }
    </style>
    <style>
      .btn-create-singer {
        background: linear-gradient(90deg, #4f8cff, #38b6ff);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 22px;
        font-size: 1.08em;
        font-weight: 500;
        cursor: pointer;
        margin-bottom: 18px;
      }
      .singer-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0; top: 0; width: 100vw; height: 100vh;
        background: rgba(24,13,38,0.45);
        align-items: center; justify-content: center;
      }
      .singer-modal-content {
        background: #fff;
        border-radius: 14px;
        max-width: 520px;
        width: 95vw;
        margin: 40px auto;
        padding: 32px 28px 24px 28px;
        position: relative;
        box-shadow: 0 2px 16px #0002;
        animation: fadeIn 0.2s;
      }
      @keyframes fadeIn { from { opacity: 0; transform: scale(0.98);} to { opacity: 1; transform: scale(1);} }
      .singer-modal-close {
        position: absolute;
        top: 18px; right: 24px;
        font-size: 2em;
        color: #888;
        cursor: pointer;
      }
      .singer-modal-content h2 {
        text-align: center;
        margin-bottom: 18px;
      }
      .singer-modal-content form label {
        font-weight: 500;
        margin-top: 10px;
        display: block;
        color: #333;
      }
      .singer-modal-content form input[type="text"], 
      .singer-modal-content form input[type="number"],
      .singer-modal-content form input[type="file"],
      .singer-modal-content form select,
      .singer-modal-content form textarea {
        width: 95%;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1.5px solid #e0e6ed;
        font-size: 1em;
        margin-bottom: 8px;
        background: #f8fafc;
      }
      .singer-modal-content form textarea {
        resize: vertical;
      }
      .btn-save-singer {
        background: #1877f2;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 0;
        font-size: 1.08em;
        font-weight: 600;
        width: 100%;
        margin-top: 10px;
        cursor: pointer;
      }
      .singer-img-preview img {
        max-width: 120px;
        max-height: 120px;
        border-radius: 10px;
        margin-top: 6px;
      }
      </style>