stm_190.xml的生成逻辑已经写完了
现在vconfig.xml是写死的
要改成自动生成
输出到output文件夹

       <file>
	    <filename>science_$this->science_issue.xml</filename>
	    <issue>$this->science_issue</issue> 
	</file>
       <file>
	    <filename>stm_$this->stm_issue.xml</filename>
	    <issue>$this->stm_issue</issue> 
	</file>
       <file>
	    <filename>signaling_$this->signaling_issue.xml</filename>
	    <issue>278</issue> 
	</file>


#### 问题解决
    # ubuntu Class 'XSLTProcessor' not found
    sudo apt-get install php5-xsl 

    # 写入权限
    chmod -R 777 codeigniter/application/pm/output/
