<?php
//Include functions.php to have access to functions like connection, header and footer
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">


<!-- Getting the Header  -->
<?php echo getHeader("Security Report") ?>

<div id=reportContainer>

    <div>
        <h1>Security Report:</h1>

        <ul>
            <h3>
                <li>Essay:</li>
            </h3>
            <h4>
                <p>
                    Regardless of the content, all the web page and web application must be secure for host and user.
                    We have sensitive data on both sides and we need to use all the possible ways to make sure hackers, viruses and frauds cannot use or manipulate the data.
                    In order to secure the web application we need to control all the accesses with proper tools and programming techniques on both server and application.
                    Securing the web applications means to check all inputs from users are sanitized, so everything will work in the way that is expected to be.
                    Securing the server includes having a proper firewall and avoiding unnecessary and unrelated services to be on the server system.
                    In the server programming side, it is important to set up security options in php.ini.(Suehring, Steve, and Janet Valade 2013).
                </p>
                <p>
                    Based on a study that sponsored by Google showed that thirty percent of encoding method is incorrect and leads to code injection vulnerabilities in the webpages(Alnabulsi, H. and Islam, R., 2018).
                    Attackers use different ways to put the website at risk. It can happen by entering queries inside the form inputs and also editing the address bar information that is sent from other pages by the get method.
                    There are some inbuilt methods in html which helps to sanitize the user input like preg_match() and htmlentites() methods which stop the user from sending malicious query to the server.
                    The attacker might enter a text in sort of a SQL query which is called SQL Injection attack which can cause changes on the database like updating or deleting a table or a record.
                    They also can manipulate the address bar and cause sending wrong data to the server, it is necessary to avoid sending sensitive data through address bars like username or data that is going to be inserted to the database.
                    There are some kinds of data that do not seem important but can cause trouble for the server, for example sending a result of a calculated data through address bar.
                    Checking the form input also includes checking for unexpected input and testing in case users enter wrong data by mistake which needs to be handled before sending data to the server.(Suehring, Steve, and Janet Valade. 2013 ).
                </p>
                <p>
                    We use sessions and cookies to store user information and pass information to other pages, however sessions are more secure as they store information on the server and the user never can see them, unlike cookies which store information in the user computer.
                    Thus web developers should not let sensitive data be stored in the user computer because it may be stolen from the user and cause problems by accessing their information. (LaCroix, 2017)
                    Although sessions seem a better choice to store all user information when they connect to the server, we should consider the memory allocation on the server for each user and set up a proper time out time, this means we can't store all user information on the server.
                    To avoid maintaining expenses for storing all the user information on the server, we can use cookies for less important information like their taste on shopping and relative ads.( MacVittie, L., 2008.)
                </p>
                <p>
                    Authentication is one of the most important parts of the web application and websites that needs to be protected from malicious attacks but unfortunately authentication protection is underestimated by web and security designers.
                    There must be layouts of protection and security checking based on the content and usage of the website or web application. For example if there is a bank application, we can't keep the security level in just relying on the HTML form-based Authentication and inbuilt methods.
                    We need to have extra unique methods on both client and server side of the website or web application to make sure information is safe. Beside using tools and codes to prevent user damage to the website, we need to ask users to be aware of the dangers and consider security measures like avoiding entering simple and easy to guess passwords by forcing them to follow a specific pattern.(Suehring, Steve, and Janet Valade. 2013 ).
                    On the server side we can protect passwords by using different methods like encryption or hash passwords. Storing passwords through encryption is used to be very popular but its not as strong as hash password because there are possibilities of decrypting password, in the other hand hashing password is a one way function, this means there is no way to discover the text from its hash and it's impossible to regenerate the text from the its hash value. (Sriramya, P. and Karthika, R.A., 2015.)
                </p>
            </h4>
            <h3>
                <li>References:</li>
            </h3>
            <h4>
                <p>Alnabulsi, H. and Islam, R., 2018, July. Web Sanitization from Malicious Code Injection Attacks. In International Conference on Applications and Techniques in Cyber Security and Intelligence (pp. 251-261). Springer, Cham.</p>
                <p> Sriramya, P. and Karthika, R.A., 2015. Providing password security by salted password hashing using bcrypt algorithm. ARPN journal of engineering and applied sciences, 10(13), pp.5551-5556.</p>
                <p> LaCroix, K., Loo, Y.L. and Choi, Y.B., 2017, July. Cookies and sessions: a study of what they are, how they work and how they can be stolen. In 2017 International Conference on Software Security and Assurance (ICSSA) (pp. 20-24). IEEE.</p>
                <p> MacVittie, L., 2008. Cookies, Sessions, and Persistence. F5 Networks Inc., F5 White Paper, pp.1-7.</p>

            </h4>
        </ul>


    </div>

</div>

<!-- Getting the Footer  -->
<?php echo getFooter() ?>

</html>