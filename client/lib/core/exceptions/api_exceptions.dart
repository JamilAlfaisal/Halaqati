

class ApiExceptions implements Exception {
  final String message;
  final int? status;
  ApiExceptions(
    this.message,
    {this.status}
  );
  @override
  String toString() => message;
}

class UnauthorizedException extends ApiExceptions{
  UnauthorizedException():
    super("Token expired. Please login again.", status: 401);
}

class NetworkException extends ApiExceptions{
  NetworkException():
    super('Network error. Check your connection.');
}