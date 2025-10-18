/**
 * products-mysql.model.ts
 * BP-api-serverless
 * MySQL version of Products model
 * Created on 2025/10/18
 * Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */

import { MySQLModel } from '../db/mysql-connection';
import { ResultSetHeader, RowDataPacket } from 'mysql2/promise';

// Base interface without RowDataPacket
export interface ProductsData {
  id?: number;
  name: string;
  category_id: number;
  brand: string;
  price: number;
  description: string | null;
  image: string | null;
  processor: string | null;
  ram: string | null;
  storage: string | null;
  screen: string | null;
  graphics: string | null;
  status: number;
  created_at: string | null;
  updated_at: string | null;
}

// Extended interface with RowDataPacket for MySQL query results
export interface Products extends ProductsData, RowDataPacket {}

export const defaultProducts: ProductsData = {
  id: undefined,
  name: '',
  category_id: 1,
  brand: '',
  price: 0,
  description: null,
  image: null,
  processor: null,
  ram: null,
  storage: null,
  screen: null,
  graphics: null,
  status: 1,
  created_at: null,
  updated_at: null,
};

/**
 * Products Model for MySQL
 * Example usage:
 * 
 * // Create instance
 * const productsModel = new ProductsMySQLModel();
 * 
 * // Find by category_id
 * const products = await productsModel.findByCategoryId(1);
 * 
 * // Create new product
 * const result = await productsModel.createProduct({
 *   name: 'MacBook Pro',
 *   category_id: 1,
 *   brand: 'Apple',
 *   price: 45990000,
 *   description: 'High-end laptop',
 * });
 * 
 * // Update product
 * await productsModel.updateProduct(1, {
 *   price: 50000000,
 *   description: 'Updated description'
 * });
 * 
 * // Delete product
 * await productsModel.deleteProduct(1);
 */
export class ProductsMySQLModel extends MySQLModel<Products> {
  constructor() {
    const tableName = 'products';
    super(tableName);
  }

  /**
   * Find all products by category_id
   * @param categoryId Category ID
   * @returns Array of product records
   */
  async findByCategoryId(categoryId: number): Promise<Products[]> {
    return await this.findBy({ category_id: categoryId });
  }

  /**
   * Find products by brand
   * @param brand Brand name
   * @returns Array of product records
   */
  async findByBrand(brand: string): Promise<Products[]> {
    return await this.findBy({ brand });
  }

  /**
   * Find products by status
   * @param status Status (1 = active, 0 = inactive)
   * @returns Array of product records
   */
  async findByStatus(status: number): Promise<Products[]> {
    return await this.findBy({ status });
  }

  /**
   * Find products by price range
   * @param minPrice Minimum price
   * @param maxPrice Maximum price
   * @returns Array of product records
   */
  async findByPriceRange(minPrice: number, maxPrice: number): Promise<Products[]> {
    const sql = `
      SELECT * FROM ${this.tableName}
      WHERE price BETWEEN ? AND ?
      ORDER BY price ASC
    `;
    return await this.query<Products>(sql, [minPrice, maxPrice]);
  }

  /**
   * Search products by name
   * @param searchTerm Search term
   * @returns Array of product records
   */
  async searchByName(searchTerm: string): Promise<Products[]> {
    const sql = `
      SELECT * FROM ${this.tableName}
      WHERE name LIKE ? OR description LIKE ?
      ORDER BY name ASC
    `;
    const searchPattern = `%${searchTerm}%`;
    return await this.query<Products>(sql, [searchPattern, searchPattern]);
  }

  /**
   * Find products with category information
   * @param limit Limit number of results
   * @param offset Offset for pagination
   * @returns Array of product records with category info
   */
  async findWithCategory(limit?: number, offset?: number): Promise<any[]> {
    const sql = `
      SELECT p.*, c.name as category_name, c.description as category_description
      FROM ${this.tableName} p
      LEFT JOIN categories c ON p.category_id = c.id
      ORDER BY p.created_at DESC
      ${limit ? `LIMIT ${limit}` : ''}
      ${offset ? `OFFSET ${offset}` : ''}
    `;
    return await this.query<any>(sql);
  }

  /**
   * Create a new product record
   * @param data Product data
   * @returns Insert result
   */
  async createProduct(data: Omit<ProductsData, 'id'>): Promise<ResultSetHeader> {
    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const productData = {
      ...data,
      created_at: data.created_at || now,
      updated_at: data.updated_at || now,
    };
    return await this.insert(productData as Partial<Products>);
  }

  /**
   * Update product record by id
   * @param id Product ID
   * @param data Data to update
   * @returns Update result
   */
  async updateProduct(
    id: number,
    data: Partial<Omit<ProductsData, 'id'>>
  ): Promise<ResultSetHeader> {
    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const updateData = {
      ...data,
      updated_at: now,
    };
    return await this.update(
      updateData as Partial<Products>,
      { id }
    );
  }

  /**
   * Delete product record by id
   * @param id Product ID
   * @returns Delete result
   */
  async deleteProduct(id: number): Promise<ResultSetHeader> {
    return await this.delete({ id });
  }

  /**
   * Count products by category_id
   * @param categoryId Category ID
   * @returns Number of product records
   */
  async countByCategoryId(categoryId: number): Promise<number> {
    return await this.count({ category_id: categoryId });
  }

  /**
   * Count products by brand
   * @param brand Brand name
   * @returns Number of product records
   */
  async countByBrand(brand: string): Promise<number> {
    return await this.count({ brand });
  }

  /**
   * Get products statistics
   * @returns Statistics object
   */
  async getStatistics(): Promise<any> {
    const sql = `
      SELECT 
        COUNT(*) as total_products,
        COUNT(CASE WHEN status = 1 THEN 1 END) as active_products,
        COUNT(CASE WHEN status = 0 THEN 1 END) as inactive_products,
        AVG(price) as average_price,
        MIN(price) as min_price,
        MAX(price) as max_price
      FROM ${this.tableName}
    `;
    return await this.queryOne<any>(sql);
  }

  /**
   * Get products by brand statistics
   * @returns Array of brand statistics
   */
  async getBrandStatistics(): Promise<any[]> {
    const sql = `
      SELECT 
        brand,
        COUNT(*) as product_count,
        AVG(price) as average_price,
        MIN(price) as min_price,
        MAX(price) as max_price
      FROM ${this.tableName}
      GROUP BY brand
      ORDER BY product_count DESC
    `;
    return await this.query<any>(sql);
  }
}
