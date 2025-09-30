import 'package:flutter/material.dart';

class FormCard extends StatelessWidget {
  final String periodo;
  final String curso;
  final String autor;
  final String titulo;
  final Color corFundo;
  final String? route;

  const FormCard({
    super.key,
    required this.periodo,
    required this.curso,
    required this.autor,
    required this.titulo,
    required this.corFundo,
    this.route,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: route != null
          ? () {
              Navigator.pushNamed(context, route!);
            }
          : null,
      child: Container(
        margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: corFundo,
          borderRadius: BorderRadius.circular(8),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              periodo.toUpperCase(),
              style: const TextStyle(
                color: Colors.white70,
                fontSize: 12,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 8),
            Text(
              curso,
              style: const TextStyle(
                color: Colors.white,
                fontSize: 20,
                fontWeight: FontWeight.w700,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              autor,
              style: const TextStyle(
                color: Colors.white70,
                fontSize: 14,
                fontWeight: FontWeight.w500,
              ),
            ),
            const SizedBox(height: 20),
            Text(
              titulo,
              style: const TextStyle(color: Colors.white, fontSize: 14),
            ),
          ],
        ),
      ),
    );
  }
}
