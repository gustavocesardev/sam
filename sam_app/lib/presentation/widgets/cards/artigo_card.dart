import 'package:flutter/material.dart';

class ArtigoCard extends StatelessWidget {
  final String dataPublicacao;
  final String titulo;
  final String autor;
  final String descricao;
  final String? route;

  const ArtigoCard({
    super.key,
    required this.dataPublicacao,
    required this.titulo,
    required this.autor,
    required this.descricao,
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
        margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
        padding: const EdgeInsets.only(bottom: 12),
        decoration: const BoxDecoration(
          border: Border(
            bottom: BorderSide(color: Colors.white12, width: 1),
          ),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              dataPublicacao,
              style: const TextStyle(
                color: Colors.white60,
                fontSize: 12,
              ),
            ),
            const SizedBox(height: 6),
            Text(
              titulo,
              style: const TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Colors.white,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              autor,
              style: const TextStyle(
                fontSize: 13,
                color: Colors.white70,
                fontStyle: FontStyle.italic,
              ),
            ),
            const SizedBox(height: 12),
            Text(
              descricao,
              style: const TextStyle(
                fontSize: 13,
                color: Colors.white,
              ),
              textAlign: TextAlign.justify,
              maxLines: 4,
              overflow: TextOverflow.ellipsis,
            ),
            SizedBox(height: 12,)
          ],
        ),
      ),
    );
  }
}
