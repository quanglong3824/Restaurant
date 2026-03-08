# Aurora Restaurant - AI Assistant Rules & Guidelines

## Project Overview
Aurora Restaurant is a comprehensive digital menu and order management system designed for restaurant operations. The system supports three main user roles: Waiters, Admins, and IT personnel, each with specific functionalities and interfaces.

## System Architecture
- **Backend**: PHP-based MVC architecture with custom routing system
- **Database**: MySQL with PDO integration
- **Frontend**: HTML/CSS/JavaScript with responsive design
- **Authentication**: Session-based with role-based access control
- **UI Framework**: Custom CSS with Font Awesome icons and Google Fonts

## Core Features

### Customer-Facing Features
- QR code-based ordering system allowing customers to order directly from tables
- Menu browsing with categories (Appetizers, Main Course, Desserts, Drinks)
- Cart functionality (add, remove, quantity adjustment)
- Support requests (call staff, payment requests)
- Multi-order capability

### Waiter Features
- Table management (open, close, merge, transfer, unmerge)
- Menu display and item selection
- Order management (add items, update quantities, confirm orders)
- Print receipts
- Access to order history
- Real-time notification system for customer requests

### Admin Features
- Staff shift management
- Menu item management (add, edit, delete, availability toggling)
- Category management
- Table management (layout, areas)
- Real-time monitoring dashboard
- Sales reporting and analytics
- Database backup functionality
- User management (for IT role)

### IT Features
- Full user management
- Database administration and backup
- System configuration

## Interface Guidelines

### Visual Design
- **Primary Color**: #D4AF37 (Gold)
- **Secondary Colors**: White, contrasting accent colors
- **Design Philosophy**: Flat design without rounded corners, modern aesthetic
- **Typography**: Google Fonts (Outfit for UI, Playfair Display for headings)

### User Experience
- Mobile-first responsive design
- Intuitive navigation with bottom bar for waiters
- Real-time notifications for service requests
- Streamlined workflow for common tasks

## AI Assistant Responsibilities

### Current State
The AI assistant is currently in development (Beta phase) as noted in the `/admin/ai` route. The UI exists but functionality is not yet implemented.

### Planned Capabilities
1. **Customer Service Automation**
   - Answer common customer questions about menu items
   - Handle basic requests (refill water, extra napkins, etc.)
   - Process payment inquiries

2. **Intelligent Menu Recommendations**
   - Suggest popular dishes based on time of day
   - Recommend complementary items
   - Personalized suggestions based on order history

3. **Business Intelligence**
   - Analyze sales trends and patterns
   - Generate insights about popular items
   - Predict peak hours and staffing needs
   - Weekly revenue analysis

4. **Operational Assistance**
   - Help staff with menu knowledge
   - Assist with order accuracy
   - Provide real-time inventory information (when integrated)

## Technical Integration Points

### API Endpoints for AI
- `/admin/ai` - Main AI assistant interface
- `/support/pending` - Customer request polling endpoint
- `/admin/reports` - Access to business analytics
- `/admin/menu` - Menu data access

### Data Sources
- Menu items and categories
- Order history and statistics
- Customer preferences (when implemented)
- Staff schedules and availability

## Development Guidelines for AI Enhancement

### Code Structure
- Follow existing MVC pattern
- Maintain consistency with current code style
- Use existing helper functions (e.g., `formatPrice`, `e()` for escaping)
- Leverage existing authentication system

### Security Considerations
- Maintain session-based authentication
- Sanitize all inputs
- Follow existing security patterns
- Respect role-based access controls

### Performance Requirements
- Optimize for mobile devices
- Minimize server load
- Efficient database queries
- Cache frequently accessed data

## Implementation Roadmap

### Phase 1: Basic AI Integration
- Simple FAQ functionality
- Menu information delivery
- Basic customer request handling

### Phase 2: Advanced Recommendations
- Machine learning-based suggestions
- Personalization engine
- Inventory integration

### Phase 3: Predictive Analytics
- Sales forecasting
- Staff scheduling assistance
- Demand prediction

## Error Handling
- Graceful degradation when AI services are unavailable
- Fallback to human staff for complex requests
- Clear error messaging to users
- Proper logging for debugging

## Testing Requirements
- Unit tests for AI functions
- Integration testing with existing systems
- User acceptance testing with restaurant staff
- Performance testing under load conditions