<?php
namespace SIM\BANKING;
use SIM;

/**
 * Test import of account statments
 * Make sure the file \wp-content\uploads\Account Statement.rtf exists
 */
function testMailImport(){
    // Insert the post into the database.
    $post   = array(
        'post_title'    => 'Oct-2023 Worker Account Statement - Nigeria: W050526 - 1178077',
        'post_content'  => '***   English below – Español a continuación – Français ci-dessous – Portugues abaixo – 한글은 아래를 보십시오   ***


        Dear colleague,
        
        A monthly account statement is attached for your review. If you have any queries, please contact the respective entity treasurer.
        
        This is an autogenerated email.  Do not reply to this email. Thank you.
          
        ________________________________________
        Estimado colega,
        
        Se adjunta el estado de cuenta mensual para su revisión. Si tiene alguna pregunta podría comunicarse con el tesorero de la entidad respectiva.
        
        Este correo electrónico se genera automáticamente. Gracias por no responder al mismo.
          
        ________________________________________
        Cher collègue,
        
        Votre relevé de compte mensuel a été envoyé en pièce jointe pour vous permettre de le vérifier. Pour toute question, veuillez contacter le trésorier de l`entité mentionnée sur le relevé.
        
        Ce courriel a été généré automatiquement. Merci de ne pas y répondre.
          
        ________________________________________
        Caro colega,
        
        Um extrato mensal da conta está anexado para sua revisão. Em caso de perguntas, entre em contato com o tesoureiro da entidade respectiva.
        
        Este é um e-mail gerado automaticamente. Não responda este email. Obrigado.
          
        ________________________________________
        사랑하는 동역자님께,
        
        월간 재정명세서를 보실 수 있도록 첨부합니다. 
        문의하실 내용이 있으면 현지의 재정담당자에게 문의하시기 바랍니다. 
        본 메일은 자동회신 메일입니다. 이 메일로는 답장을 할 수 없습니다. 
        감사합니다.
        ',
        'post_status'   => 'publish'
    );

    $post['ID'] = wp_insert_post($post );

    wp_insert_attachment(
        array(
            'post_title'        => 'SIM Nigeria_W050526 - Harmsen, Ewald and  Lianne_3_11 January 2024,  091300.pdf',
            'post_content'      => 'test',
            'post_mime_type'    => 'application/pdf',
        ),
        "2023-10 SIM Nigeria_W050526 - Harmsen, Ewald and  Lianne_3_11 January 2024,  091300.pdf",
        $post['ID']
    );

    postieBeforeFilter($post);
}