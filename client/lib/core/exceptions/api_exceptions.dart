

class ApiException implements Exception {
  final String message;
  final int? statusCode;
  ApiException(
    this.message,
    {this.statusCode}
  );
  @override
  String toString() => message;
}

class UnauthorizedException extends ApiException{
  UnauthorizedException():
    super("Token expired. Please login again.", statusCode: 401);
}

class NetworkException extends ApiException{
  NetworkException():
    super('Network error. Check your connection.');
}