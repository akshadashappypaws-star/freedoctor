## 🔧 **Google Maps API Key Fix Instructions**

### **Problem:**
`RefererNotAllowedMapError` - The API key restrictions are blocking `http://127.0.0.1:8000`

### **Solution:**

1. **Go to Google Cloud Console:**
   - Visit: https://console.cloud.google.com/
   - Navigate to "APIs & Services" > "Credentials"

2. **Find Your API Key:**
   - Look for: `AIzaSyAaAGMxei5r_4i_3Yw2LBNLtu56KE5mVek`
   - Click on the API key name

3. **Update Application Restrictions:**
   - Under "Application restrictions", select "HTTP referrers (web sites)"
   - Click "Add an item" and add these referrer patterns:

   ```
   127.0.0.1:8000/*
   localhost:8000/*
   127.0.0.1/*
   localhost/*
   *.freedoctor.in/*
   freedoctor.in/*
   *.freedoctor.world/*
   freedoctor.world/*
   ```

4. **Verify API Services are Enabled:**
   - Go to "APIs & Services" > "Library"
   - Make sure these APIs are enabled:
     - ✅ Maps JavaScript API
     - ✅ Places API
     - ✅ Geocoding API

5. **Save Changes and Test:**
   - Click "Save" 
   - Wait 5-10 minutes for changes to propagate
   - Refresh the campaigns page

### **Current Status:**
- ✅ API key is valid and loading
- ✅ User location detection works (18.6240471, 73.9172526)
- ✅ Location-priority search implemented
- ❌ Referrer restrictions blocking development domain
- ⚠️ Places Autocomplete deprecated (but still working)

### **Fallback:**
The system now works with manual location entry even if Google Maps fails!
