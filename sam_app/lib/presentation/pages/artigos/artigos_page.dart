import 'package:flutter/material.dart';

class ArtigosPage extends StatefulWidget {
  const ArtigosPage({super.key});

  @override
  State<ArtigosPage> createState() => _ArtigosPageState();
}

class _ArtigosPageState extends State<ArtigosPage> {
  @override
  Widget build(BuildContext context) {
    return Center(
      child: Text('Artigos'),
    );
  }
}