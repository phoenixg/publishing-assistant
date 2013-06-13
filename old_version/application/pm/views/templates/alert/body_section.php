<!-- section -->
<?php
/*
 * Each newsletter is segmented in sections (special, news, etc...). This builds those segments.
 *
 */
    //variables
    $horizon_rule = '<tr><td height="10" valign="bottom"><hr style="border: 0; height: 0; border-top: 1px solid #ffffff; border-bottom: 1px solid #cacaca; margin: 0 15px; border-left:0px solid #ffffff;" /></td></tr>'; // the horizontal rule that is used over and over
    foreach ($order as $section=>$sectionData){
      $special = ($section == "Special Section" && ! is_numeric($section));
						

        //print out the section title
								if(! is_numeric($section)){
									print $horizon_rule;
									print '<tr> <td class="section-title" valign="bottom" height="22" style="padding: 0 20px; font-family: Verdana, sans-serif; text-transform: uppercase; font-size: 12px; font-weight: bold; color: #a00000;">';
									print $section;
									print ' </td></tr>';
									print $horizon_rule;
								}
        
        //open the section content td
        //check if the section is a special section, if it is, make it stand out. 
        if($special){
            print'<tr> <td class="content-copy" valign="top" style="padding: 0 20px; color: #000; font-size: 14px; font-family: Georgia; mso-line-height-rule:exactly; line-height: 20px;" colspan="2"><div style="background-color:#f3f3f3; padding:  0 12px 12px 12px; margin-top: 1.3em">';
												//unset($special);
								}elseif (isset($section)){
            print'<tr> <td class="content-copy" valign="top" style="padding: 0 20px; color: #000; font-size: 14px; font-family: Georgia; mso-line-height-rule:exactly; line-height: 20px;" colspan="2"><div>';
        }  
                
        $article_type_last = ''; // used for special issue article type section.
        foreach($sectionData as $sectionTitle=>$group){
        
            if($group['display'] == 'full'){
                
                foreach($group['content'] as $index=>$Articlecont){
										
                    //if the index is numeric, then it's an article, otherwise it's a php include.
                    if(is_numeric($index)){
																								print '<br />';

																								if(! empty($Articlecont['articleType'] ) && $special && $Articlecont['articleType'] != $article_type_last){
																											print '<div style="height: 10px; border-bottom: solid thin #a8b2b8;"></div>';
																											print '<div style="font-size: 10px; font-family:arial, helvetica, sans-serif; color: #0e6699; letter-spacing: 1px;">'.$Articlecont['articleType'].'</div>';
																											print '<div style="height: 10px; border-top: solid thin #a8b2b8;"></div>';
																											$article_type_last = $Articlecont['articleType'];
																								}

																								if(! empty($Articlecont['overline'] )){
																																																	print '<div class="overline" style="font-size: 10px; font-family:arial, helvetica, sans-serif; color: #999; letter-spacing: 1px;">'.$Articlecont['overline'].'</div>';
																																												
																								}

                        print ' <div class="item-title" style="font-size: 15px; margin-bottom: 4px"><strong><a href="'.$Articlecont['url'].'">'.$Articlecont['title'].'</a></strong></div>';  
                        
                        //print the authors
                        if(! empty($Articlecont['author'] )){
                            print '<div class="byline" style="font-size: 12px; font-style:italic; color: #666;">'.$Articlecont['author'].'</div>';
                        }
                        
                        //print the teaser
                        if(! empty($Articlecont['teaser'] )){
                            print '<div class="teaser" style="color:#444; font-family: arial, sans-serif; font-size: 12px;">'.$Articlecont['teaser'].'</div>';
                        }
                        
                    
                 
                //if the index is not numeric, then it's an include, in which case we just need to print the include. [more than likely this is an in content ad.]  
                }else{
                        print read_file('application/pm/output/ads/'.$jname."_".$alert."_".$Articlecont);
                        print '<br/>';
                    }
                }
                
            //if the display is set to collapsed, then we will take out the content of this section, and instead simply return the predefined linked title and teaser (if provided).
            }elseif($group['display'] == 'collapsed'){
                print '<br />';				
		print '<div class="item-title" style="font-size: 15px; margin-bottom: 4px"><strong><a href="'.str_replace('%$issue', $issue,str_replace('%$vol', $vol, $group['link-override'])).'">'.$sectionTitle.'</a></strong></div>';
                    if(! empty($group['description'])){
			print '<div class="teaser" style="color:#444; font-family: arial, sans-serif; font-size: 12px;">'.$group['description'].'</div>';
		    }
                
            }
            
            // Set the current section portion for the special issue callout box. 
            // If this changes on the next pass, print it; otherwise, skip it.
        }
        
        //close the section content td
								if (isset($section)){
									print '</div><br/></td></tr>';
								}
               
        
    }//end section 

?>
