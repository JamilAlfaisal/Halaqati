import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:halqati/models/student.dart';
import 'package:halqati/models/halaqa_class.dart';


class ApiService {
  // 💡 IMPORTANT: Use the correct IP for your emulator/device
  // Android Emulator: 10.0.2.2
  // iOS Simulator: 127.0.0.1 (or localhost)
  // Physical Device (if on same network): Your PC's local IP (e.g., 192.168.1.10)
  static const String _baseUrl = 'http://10.0.2.2:8000/api';

  Future <Map<String, dynamic>?> login(String phone, String pin) async {
    final url = Uri.parse('$_baseUrl/auth/login');

    try {
      final response = await http.post(
        url,
        // Crucial headers to tell Laravel to expect JSON data (Content-Type)
        // and return a JSON response (Accept)
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        // Encode the Dart Map into a JSON String for the request body
        body: jsonEncode({
          'phone': phone,
          'pin': pin,
        }),
      );

      final responseBody = jsonDecode(response.body);

      if (response.statusCode == 200) {
        // ✅ Successful Login
        final token = responseBody['token'] as String?;
        // print('Login Success! Token: $token');
        // print(responseBody);
        return responseBody;

      } else if (response.statusCode >= 400 && response.statusCode < 500) {
        // ❌ Client Error (403 Unauthorized, 422 Validation)
        // Check for specific error message structure from Laravel
        final message = responseBody['message'] ?? 'Authentication failed.';
        print('Login Failed: $message');

        // You might want to extract and display validation errors here:
        // final errors = responseBody['errors'];

        return null;

      } else {
        // ❌ Server Error (5xx)
        print('Server Error: ${response.statusCode}');
        return null;
      }

    } catch (e) {
      // ❌ Network/Connection Error
      print('Network connection failed or data invalid: $e');
      return null;
    }
  }

  Future<bool> createClass({
    required String token, // The authentication token
    required String name,
    required String description,
    required int capacity,
    required String roomNumber,
    required List<String> scheduleDays, // List of days
    required String scheduleTime,       // Time string
  }) async {
    final url = Uri.parse('$_baseUrl/classes'); // Assuming your endpoint is /api/classes

    // 1. Construct the complete request body Map
    final Map<String, dynamic> requestBody = {
      "name": name,
      "description": description,
      "capacity": capacity,
      "room_number": roomNumber,
      "schedule": {
        "days": scheduleDays,
        "time": scheduleTime,
      },
    };

    try {
      final response = await http.post(
        url,
        headers: {
          // 🚨 CRITICAL: Include the Bearer token in the Authorization header
          'Authorization': 'Bearer $token',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode(requestBody), // Encode the whole Map to JSON
      );

      if (response.statusCode == 201) {
        // ✅ Success: 201 Created is the standard for successful creation
        print('Class created successfully!');
        return true;
      } else {
        // ❌ Failure (4xx or 5xx)
        final responseBody = jsonDecode(response.body);
        print('Class Creation Failed (Status ${response.statusCode}): ${responseBody['message']}');
        return false;
      }
    } catch (e) {
      // ❌ Network or Parsing Error
      print('Failed to connect or process data: $e');
      return false;
    }
  }

  Future<List<HalaqaClass>> getClasses(String token) async {
    final url = Uri.parse('$_baseUrl/classes');

    try {
      final response = await http.get(
        url,
        headers: {
          'Authorization': 'Bearer $token',
          'Accept': 'application/json',
        },
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        // print(data);
        // depending on your Laravel API structure
        if (data != null) {
          final classList = data as List;
          // print(classList);
          return classList.map((json) => HalaqaClass.fromJson(json["class"])).toList();
        }
        print("Unexpected API response structure");
        return [];
      } else {
        print("Fetch Classes Failed: ${response.statusCode}");
        return [];
      }
    } catch (e) {
      print("Network Error: $e");
      return [];
    }
  }

  Future<List<Student>?> getStudentsByClass(String token, int classId)async{
    final url = Uri.parse('$_baseUrl/classes');
    try{
      print("getStudentsByClass");
      final response = await http.get(
        url,
        headers: {
          'Authorization':'Bearer $token',
          'Access':'application/json'
        }
      );
      if(response.statusCode == 200){
        final data = jsonDecode(response.body);
        // print(data[classId]['class']['students']);
        final studentsList = data[classId]['class']['students'] as List;
        // print(studentsList);
        return studentsList.map((json)=> Student.fromJson(json)).toList();
      }else{
        print("Fetch students Failed: ${response.statusCode}");
        return null;
      }
    }catch(e){
      print("Network Error: $e");
    }
  }
}