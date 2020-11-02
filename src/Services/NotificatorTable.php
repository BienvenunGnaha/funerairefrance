<?php


namespace App\Services;


use Symfony\Component\Security\Core\User\UserInterface;

class NotificatorTable
{
    public function notificatorIndexForeignKey(UserInterface $user){
        $id = $user->getId();
        $idx_post = 'IDX_LIKE_POST_NOTIFICATOR_'.$id;
        $idx_comment_post = 'IDX_LIKE_COMMENT_POST_NOTIFICATOR_'.$id;
        $idx_response_post = 'IDX_LIKE_RESPONSE_COMMENT_POST_NOTIFICATOR_'.$id;
        $idx_profile = 'IDX_LIKE_PROFILE_NOTIFICATOR_'.$id;
        $idx_comment_profile = 'IDX_LIKE_COMMENT_PROFILE_NOTIFICATOR_'.$id;
        $idx_response_profile = 'IDX_LIKE_RESPONSE_COMMENT_PROFILE_NOTIFICATOR_'.$id;
        $idx_user = 'IDX_USER_NOTIFICATOR_'.$id;
        $idx_like = 'IDX_LIKE_NOTIFICATOR_'.$id;
        $idx_kiss = 'IDX_KISS_NOTIFICATOR_'.$id;
        $fk_post = 'FK_LIKE_POST_NOTIFICATOR_'.$id;
        $fk_comment_post = 'FK_LIKE_COMMENT_POST_NOTIFICATOR_'.$id;
        $fk_response_post = 'FK_LIKE_RESPONSE_COMMENT_POST_NOTIFICATOR_'.$id;
        $fk_profile = 'FK_LIKE_PROFILE_NOTIFICATOR_'.$id;
        $fk_comment_profile = 'FK_LIKE_COMMENT_PROFILE_NOTIFICATOR_'.$id;
        $fk_response_profile = 'FK_LIKE_RESPONSE_COMMENT_PROFILE_NOTIFICATOR_'.$id;
        $fk_like = 'FK_LIKE_NOTIFICATOR_'.$id;
        $fk_kiss = 'FK_KISS_NOTIFICATOR_'.$id;
        $fk_user = 'FK_USER_NOTIFICATOR_'.$id;


        return array('idx_post' => $idx_post, 'idx_comment_post' => $idx_comment_post, 'idx_response_post' =>$idx_response_post, 'idx_profile' => $idx_profile,
            'idx_comment_profile' => $idx_comment_profile, 'idx_response_profile' => $idx_response_profile, 'fk_post' => $fk_post,
            'fk_comment_post' => $fk_comment_post, 'fk_response_post' => $fk_response_post, 'fk_profile' => $fk_profile, 'fk_comment_profile' => $fk_comment_profile,
            'fk_response_profile' => $fk_response_profile, 'idx_user' => $idx_user, 'fk_user' => $fk_user, 'idx_like' => $idx_like, 'idx_kiss' => $idx_kiss,
            'fk_like' => $fk_like, 'fk_user' => $fk_user, 'fk_kiss' => $fk_kiss);
    }
}
