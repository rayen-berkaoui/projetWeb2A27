# GreenMind Project

## Accessing Your Frontend Integration

To view your frontend with the newly integrated Aranyak template, follow these steps:

### Prerequisites
1. Make sure XAMPP is running (Apache and MySQL services)
2. Verify your database connection is working

### Access URLs

Your frontend is accessible at the following URLs:

- **Homepage**: http://localhost/greenmind/
- **Feedback Form**: http://localhost/greenmind/index.php?action=form
- **Feedback Display**: http://localhost/greenmind/index.php?action=display

### Database Access

Your feedback database can be accessed at:
- PHPMyAdmin: http://localhost/phpmyadmin/
- Database: `avis_db`
- Table: `feedbacks`

### Troubleshooting

If you cannot access your frontend:

1. Verify XAMPP is running:
   - Open XAMPP Control Panel
   - Check that Apache and MySQL are running (green status)
   - If not, start them by clicking "Start" buttons

2. Check file permissions:
   - Ensure all project files have appropriate read permissions

3. Browser cache issues:
   - Try clearing your browser cache or opening in incognito/private mode

4. Database connection:
   - Verify database credentials in your config file

### Project Structure

The Aranyak template has been integrated with your feedback system using MVC architecture:

- **Model**: `\app\model\FeedbackModel.php` - Handles database operations
- **View**: 
  - `\app\view\front\components\FeedbackForm.php` - Stylized feedback form
  - `\app\view\front\components\FeedbackSection.php` - Stylized feedback display
- **Controller**: `\app\controller\FeedbackController.php` - Controls form submission and display

### Frontend Components

The following components have been created:

1. **FeedbackForm.php**: 
   - Interactive star rating system
   - Form validation
   - Responsive design
   - Modern Aranyak styling

2. **FeedbackSection.php**:
   - Display of approved feedback with star ratings
   - Average rating display
   - Pagination
   - Responsive design

### Style Customization

To modify the appearance:
- Color scheme variables are defined at the top of each component in `:root`
- The primary color (#65B741) can be changed throughout the theme

