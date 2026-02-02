# Advanced Search Features for Roomie Application

This document describes the enhanced search functionality added to the Roomie application, allowing users to search for houses/rooms with advanced filters, location-based search, and search history tracking.

## Features Implemented

### 1. Enhanced Property Search
Search for rooms and properties using multiple criteria:

- **Property Type**: Filter by `room` or `apartment`
- **BHK**: Filter by number of bedrooms (for apartments)
- **Budget**: Set minimum and maximum price range per night
- **Locality**: Search by location name in property title or description

### 2. Location-Based Search
Find nearest properties and rooms using geographic coordinates:

- **Nearest Properties**: Find properties within a specified radius
- **Nearest Rooms**: Find rooms within a specified radius
- **Distance Calculation**: Uses Haversine formula for accurate distance calculation

### 3. Search History
Track and retrieve user's search history:

- **Automatic Saving**: Searches are automatically saved for authenticated users
- **Search History Retrieval**: Get recent search queries
- **Search Analytics**: Track search patterns and results count

### 4. Popular Locations
Get trending search locations based on user search patterns.

## API Endpoints

All endpoints require authentication via Sanctum token.

### Search Properties/Rooms
```
GET /api/search
```

**Query Parameters:**
- `property_type` (string): `room` or `apartment`
- `bhk` (integer): Number of bedrooms (1-10)
- `min_budget` (decimal): Minimum price per night
- `max_budget` (decimal): Maximum price per night
- `locality` (string): Search term for location
- `latitude` (decimal): Latitude for location-based search (-90 to 90)
- `longitude` (decimal): Longitude for location-based search (-180 to 180)
- `radius_km` (integer): Search radius in kilometers (1-100, default: 10)
- `page` (integer): Page number for pagination
- `per_page` (integer): Items per page (1-100, default: 20)

**Response:**
```json
{
  "message": "Search completed successfully",
  "data": {
    "current_page": 1,
    "per_page": 20,
    "total": 15,
    "last_page": 1,
    "items": [...]
  }
}
```

### Get Search History
```
GET /api/search/history
```

**Query Parameters:**
- `limit` (integer): Number of recent searches to retrieve (default: 10)

**Response:**
```json
{
  "message": "Search history retrieved successfully",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "property_type": "apartment",
      "bhk": 2,
      "min_budget": "1000.00",
      "max_budget": "5000.00",
      "locality": "Downtown",
      "latitude": "40.7128",
      "longitude": "-74.0060",
      "radius_km": 10,
      "results_count": 15,
      "created_at": "2026-01-30T12:00:00.000000Z"
    }
  ]
}
```

### Find Nearest Properties/Rooms
```
GET /api/search/nearest
```

**Query Parameters:**
- `latitude` (decimal, required): Latitude coordinate
- `longitude` (decimal, required): Longitude coordinate
- `radius_km` (integer): Search radius in kilometers (default: 10)
- `limit` (integer): Maximum results to return (default: 20)

**Response:**
```json
{
  "message": "Nearest properties and rooms found successfully",
  "data": {
    "properties": [
      {
        "id": 1,
        "title": "Downtown Apartment",
        "description": "Modern apartment in city center",
        "latitude": "40.7128",
        "longitude": "-74.0060",
        "distance_km": 2.5
      }
    ],
    "rooms": [
      {
        "id": 1,
        "property_id": 1,
        "room_number": "101",
        "room_type": "private",
        "price_per_night": "1500.00",
        "distance_km": 2.5
      }
    ],
    "search_location": {
      "latitude": "40.7128",
      "longitude": "-74.0060",
      "radius_km": 10
    }
  }
}
```

### Get Popular Locations
```
GET /api/search/popular-locations
```

**Query Parameters:**
- `limit` (integer): Number of popular locations to retrieve (default: 10)

**Response:**
```json
{
  "message": "Popular locations retrieved successfully",
  "data": [
    "Downtown",
    "Midtown",
    "Brooklyn",
    "Queens"
  ]
}
```

## Database Schema

### Search Histories Table
```sql
CREATE TABLE search_histories (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  property_type VARCHAR(255),
  bhk INT,
  min_budget DECIMAL(10,2),
  max_budget DECIMAL(10,2),
  locality VARCHAR(255),
  latitude DECIMAL(10,8),
  longitude DECIMAL(11,8),
  radius_km INT DEFAULT 10,
  search_filters JSON,
  results_count INT DEFAULT 0,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Usage Examples

### Basic Property Search
```bash
GET /api/search?property_type=apartment&bhk=2&min_budget=1000&max_budget=3000
```

### Location-Based Search
```bash
GET /api/search?latitude=40.7128&longitude=-74.0060&radius_km=5
```

### Combined Search
```bash
GET /api/search?property_type=room&min_budget=500&max_budget=1500&locality=Downtown&latitude=40.7128&longitude=-74.0060&radius_km=10
```

### Get Recent Searches
```bash
GET /api/search/history?limit=5
```

### Find Nearest Properties
```bash
GET /api/search/nearest?latitude=40.7128&longitude=-74.0060&radius_km=15&limit=10
```

## Technical Implementation

### Services Used

1. **SearchServices** (`app/Services/HomeService/SearchServices.php`)
   - Handles main search logic
   - Integrates with LocationService
   - Manages search history

2. **LocationService** (`app/Services/LocationService.php`)
   - Calculates distances using Haversine formula
   - Finds nearest properties/rooms
   - Placeholder for geocoding APIs

### Models

1. **SearchHistory** (`app/Models/SearchHistory.php`)
   - Stores user search queries
   - Provides search analytics methods

### Validation

- **SearchRequest** (`app/Http/Requests/SearchRequest.php`)
  - Validates all search parameters
  - Provides custom error messages

### Location Features

The location service uses the Haversine formula to calculate distances between coordinates:

```
Distance = 6371 * acos(cos(lat1) * cos(lat2) * cos(lon2 - lon1) + sin(lat1) * sin(lat2))
```

Where 6371 is Earth's radius in kilometers.

## Future Enhancements

1. **Geocoding Integration**: Integrate with Google Maps or OpenStreetMap for address-to-coordinates conversion
2. **Search Suggestions**: Auto-complete for localities based on search history
3. **Advanced Filters**: Additional filters like amenities, furnishing, gender preference
4. **Search Analytics**: Dashboard for admins to view search trends
5. **Caching**: Cache popular search results for better performance
6. **Real-time Search**: WebSocket integration for live search results

## Testing

To test the search functionality:

1. Ensure you have authenticated users with Sanctum tokens
2. Create sample properties with latitude/longitude coordinates
3. Test each endpoint with various parameter combinations
4. Verify search history is being saved correctly
5. Test location-based search with real coordinates

## Performance Considerations

- Location-based searches use database spatial queries for efficiency
- Search history is limited to prevent database bloat
- Pagination is implemented to handle large result sets
- Consider adding database indexes on frequently searched columns
