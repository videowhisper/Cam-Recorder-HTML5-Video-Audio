## Cam-Recorder-HTML5-Video-Audio : Video/Audio Recorder Camera/Microphone HTML5 - Standalone PHP

[Live Demo: Cam/Mic Video/Audio Recorder HTML5 - Plain PHP](https://demo.videowhisper.com/cam-recorder-html5-video-audio/)

![Cam/Mic Video/Audio Recorder HTML5](/snapshots/h5v-video-recorder.jpg)


Before installing, test the simple setup in the live demo:
[Cam/Mic Recorder HTML5 - Live Demo](https://demo.videowhisper.com/cam-recorder-html5-video-audio/)

This edition implements audio / video recording in an instant recording booth.

### Simple PHP Edition Features: Cam Recorder HTML5
 * [x] Instant recording booth
 * [x] Access recorder with a button
 * [x] List recordings (video, audio)

### Installation Instructions  
Requirements: Regular web hosting with PHP. Recording does not involve live streaming requirements. This edition does not implement conversions.

 1. Deploy files to your web installation location. (Example: yoursite.domain/cam-recorder-html5-video-audio/)
 2. If you don't have SuPHP, enable write permissions (0777) for folder "uploads", required to save session and chat info.

### Compatible Turnkey Hosting with Free Installation
* [Live Streaming Hosting](https://webrtchost.com/hosting-plans/) - includes live streaming servers, web hosting with video conversion
* [Video Hosting](https://videosharevod.com/hosting/) - web hosting with video conversions

### Plain PHP Edition Limitations
 * The plain php edition refers to minimal scripts to showcase recording video and audio. 
 * Plain php edition does not involve database and systems to manage members, rooms, billing. These depend on framework you want to integrate, plugins, database, member system. 
 * See [Turnkey HTML5 Videochat Site](https://paidvideochat.com/html5-videochat/) edition, available as WordPress plugin with full php source. Includes user role management (performers/clients), pay per minute, integrates billing wallets.

### Main Integration Scripts
 * index.php : room booth page with Recorder button, recordings, accessed directly creates a recording booth and shows room link to access later
 * recorder.php : embeds video / audio recorder app
 * app-call.php is called by application to retrieve parameters, interact with web server, update status and chat (ajax calls)
 * app-functions.php functions implementing features for app-call.php , including translated texts, app settings
 
Scripts also contain comments for clarifications/suggestions. 

### VideoWhisper HTML5 Project Demos
 * [Video Call - HTML5 Videochat](https://demo.videowhisper.com/videocall-html5-videochat/)
 * [Live Streaming - HTML5 Videochat](https://demo.videowhisper.com/html5-videochat/)
 * [Cam/Mic Recorder HTML5](https://demo.videowhisper.com/cam-recorder-html5-video-audio/)
 
 ### VideoWhisper HTML5 Project on GitHub
 * [Video Call - HTML5 Videochat](https://github.com/videowhisper/VideoCall-HTML5-Videochat-PHP)
 * [Live Streaming - HTML5 Videochat](https://github.com/videowhisper/HTML5-Videochat-PHP)
 * [Cam/Mic Recorder HTML5](https://github.com/videowhisper/Cam-Recorder-HTML5-Video-Audio)

For a free consultation, [Contact VideoWhisper Technical Support](https://videowhisper.com/tickets_submit.php).