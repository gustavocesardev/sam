
class ApiMessageUtils {
  static String extractMessageFromResponse(dynamic rawMessage) {
    if (rawMessage is String) {
      return rawMessage;
    } else if (rawMessage is Map) {
      final firstKey = rawMessage.keys.first;
      final firstValue = rawMessage[firstKey];
      if (firstValue is List && firstValue.isNotEmpty) {
        return firstValue.first.toString();
      } else {
        return 'Erro desconhecido.';
      }
    } else {
      return 'Erro ao processar a resposta da API.';
    }
  }
}