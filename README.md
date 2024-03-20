## About The Project

* This project is a robust and efficient solution for creating and sending [WizWiz Bot](https://github.com/wizwizdev/wizwizxui-timebot) database backups to a Telegram chat. It utilizes PHP and the Telegram Bot API to automate the backup process, ensuring that your valuable data is securely stored and easily accessible.

### Features

- **Automated Database Backup**: The script seamlessly creates a backup of your specified database using the `mysqldump` command.
- **Secure File Transfer**: The generated backup file is securely sent to a designated Telegram chat using the Telegram Bot API and cURL requests.
- **Error Handling and Logging**: Comprehensive error handling and logging mechanisms are implemented to track and resolve any issues that may arise during the backup process.
- **Flexible Configuration**: Database credentials, Telegram bot token, and chat ID can be easily configured by modifying the `baseInfo.php` file.
- **Automatic File Compression**: If the backup file size exceeds the Telegram API limit (20 MB), the script automatically compresses the file using `gzip` to ensure successful transfer.
- **Cleanup Mechanism**: After successfully sending the backup file, the script removes the local backup file(s) to conserve disk space.

## Getting Started

1. Clone the `wizwiz-backup.php` somewhere in `/public_html` for ex. `/public_html/wizwizxui-timebot`.
2. Ensure that the required PHP extensions (`curl` and `zlip(gzip)`) are enabled on your server.
3. Set up a cron job or scheduled task to run the script at your desired intervals.

- Option 1: Using cURL
```
curl https://dDOMAIN.COM/wizwizxui-timebot/wizwiz-backup.php >/dev/null 2>&1
```
_Replace `dDOMAIN.COM` with your actual domain name._

- Option 2: Using PHP Command
```
/usr/local/bin/php /home/uUSERNAME/public_html/wizwizxui-timebot/wizwiz-backup.php
```
_Replace `uUSERNAME` with your actual username._

## Conclusion

- **Congratulations!** You have successfully set up and automated the backup script for the WizWiz bot. The script will retrieve data from the specified URL and create gzipped backup files on a regular basis.
- **Happy backing up!**


## Contributing

Contributions to this project are welcome! If you find any issues or have suggestions for improvements, please feel free to open an issue or submit a pull request. Make sure to follow the project's coding standards and guidelines.

## Acknowledgments

- [Telegram Bot API](https://core.telegram.org/bots/api) for providing the powerful messaging platform and API.
- [cURL](https://curl.se/) for enabling seamless HTTP requests within PHP.
- [MySQL](https://www.mysql.com/) for the reliable and efficient database management system.
- [gzip](https://www.gzip.org/) for the compression functionality.
