<?php
if(!defined('IN_CMS')) exit;

return [
  'label'                          =>    'home/other/label',
  'sitemap'                        =>    'home/other/sitemap',
  'tags/:id/:page'                 =>    'home/other/tags',
  'tags/:id'                       =>    'home/other/tags',
  'downurl/:id'                    =>    'home/index/down',
  'love/:id'                       =>    'home/index/love',
  'like/:id'                       =>    'home/index/like',
  'buy/:id'                        =>    'home/index/buy',
  'refresh'                        =>    'pay/index/index',
  'openid'                         =>    'pay/index/openid',

  'ucenter/:id/:page'              =>    'home/index/center',
  'ucenter/:id'                    =>    'home/index/center',

  'list/:classid/:filter/:page'    =>    'home/index/cate',
  'list/:classid/:filter'          =>    'home/index/cate',
  'list/:classid/page/:page'       =>    'home/index/cate',
  'list/:classid'                  =>    'home/index/cate',

  'show/:id'                       =>    'home/index/show',

  'search/:page'                   =>    'home/other/search',
  'search'                         =>    'home/other/search',
  
  'comment/:id/:page'              =>    'home/comment/index',
  'comment/:id'                    =>    'home/comment/index',
  'more/:pid/:page'                =>    'home/comment/more',
  'more/:pid'                      =>    'home/comment/more',
  
  'user'                           =>    'home/user/index',
  'tips'                           =>    'home/user/tips',
  'app'                            =>    'home/user/app',
  'auth'                           =>    'home/user/auth',
  'login/:type'                    =>    'home/user/login',
  'login'                          =>    'home/user/login',
  'reg/:uid'                       =>    'home/user/reg',
  'reg'                            =>    'home/user/reg',
  'getpass'                        =>    'home/user/getpass',
  'out'                            =>    'home/user/out',
  'editpass'                       =>    'home/user/editpass',
  'upgrade/:id'                    =>    'home/user/upgrade',
  'upgrade'                        =>    'home/user/upgrade',
  'bind'                           =>    'home/user/bind',

  'mymoney/:page/:type'            =>    'home/user/money',
  'mymoney/:page'                  =>    'home/user/money',
  'mymoney/type/:type'             =>    'home/user/money',
  'mymoney'                        =>    'home/user/money',
  
  'mylove/:page'                   =>    'home/user/love',
  'mylove'                         =>    'home/user/love',
  'mybuy/:page'                    =>    'home/user/buy',
  'mybuy'                          =>    'home/user/buy',
  'mydown/:page'                   =>    'home/user/down',
  'mydown'                         =>    'home/user/down',
  'mybill/:page'                   =>    'home/user/bill',
  'mybill'                         =>    'home/user/bill',

  'myline/:page/:type'           =>    'home/user/line',
  'myline/:page'                 =>    'home/user/line',
  'myline/type/:type'            =>    'home/user/line',
  'myline'                       =>    'home/user/line',

  'myordershow/:id'                =>    'home/user/ordershow',
  'myorder/:page/:type'            =>    'home/user/order',
  'myorder/:page'                  =>    'home/user/order',
  'myorder/type/:type'             =>    'home/user/order',
  'myorder'                        =>    'home/user/order',

  'post/:page/:type'               =>    'home/user/post',
  'post/:page'                     =>    'home/user/post',
  'post/type/:type'                =>    'home/user/post',
  'post-add'                       =>    'home/user/postadd',
  'post-edit/:id'                  =>    'home/user/postedit',
  'post'                           =>    'home/user/post',
];