<style>

.sidebar-left {
    float: left;
}

.left-sidenav {
    width: 180px;
    padding: 0;
    background-color: #fff;
    border-radius: 3px;
    -webkit-border-radius: 3px;
    border: 1px solid #e5e5e5;
    margin-bottom: 20px;
}

.left-sidenav li {
    border-bottom: 1px solid #e5e5e5;
}

.left-sidenav>li>a {
    display: inline-block;
    width: 176px \9;
    margin: 0;
    /*padding: 8px 0 0 14px !important;*/
    height:auto;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width:auto;
	font-family:Arial, Helvetica, sans-serif;
	font-size:13px;
	text-decoration:none;
}

.nav-list>li>a {
    padding:5px 0 2px 20px;
    color:#222;
}

.left-sidenav>li:hover{background-color:#eee}.

.left-sidenav>.active>a {
    position: relative;
    z-index: 2;
    border-right: 0 !important;
}

.nav-list>.active>a, .nav-list>.active>a:hover {
    padding-left:16px;
    font-weight: bold;
    color: #dd4b39;
    text-shadow: none;
    background-color: transparent;
    border-left: 3px solid #dd4b39;
}

</style>


            <?php $uri = $_SERVER['REQUEST_URI'];
            @$uri1=explode('/',$uri);
            @$uri2=@$uri1[2]."/".@$uri1[3]."/".@$uri1[4];  ?>

<aside class="sidebar-left">
<ul class="nav nav-list left-sidenav">
       <li <?php if($uri2 == "master/master/manage_account") { ?>class="active" <?php } ?> >
        <a href="<?=base_url('master/master/manage_account');?>">Billing Details</a></li>              
       <!-- <li <?php if($uri2 == "master/master/master") { ?>class="active" <?php } ?> >
        <a href="<?=base_url('master/master/master');?>">Account Owner</a></li> -->
 </ul>
</aside>
